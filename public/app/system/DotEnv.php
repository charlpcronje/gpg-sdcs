<?php

/**
 * Class DotEnv - Reads, parses .env files.
 * It can update the $_ENV, $_SERVER and env.
 * It can also define the found values as php constants if they are not defined just yet
 * $dot_env->init(['prefix' => 'APP_SANDBOX_', 'scan_dirs' => [ APP_SANDBOX_PROJECT_BASE_DIR, ] ]);
 */
class DotEnv {
    /**
     * @var array
     */
    private array $params = [];
    public array $processed = [];

    /**
     * Singleton
     * @staticvar static $instance
     * @return static
     */
    public static function instance(): ?DotEnv {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new static();
        }
        return $instance;
    }

    public function init($params = []) {
        $this->params = $params;
    }

    /**
     * Updates env, $_ENV, $_SERVER with the passed data. If value is non a scalar it will be json encoded.
     * @param array $data
     * @param bool $override
     * @return bool
     */
    public function updateEnv(array $data = [], bool $override = false): bool {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        $prefix = '';

        if (!empty($this->params['prefix'])) {
            $prefix = $this->formatPrefix($this->params['prefix']);
        }

        foreach ($data as $k => $v) {
            if (!$this->hasPrefix($prefix, $k)) {
                $k = $prefix . $k;
            }
            $v = is_scalar($v) ? $v : json_encode($v);
            $this->processed[$k] = $v;

            if ($override) {
                putenv("$k=" . $v);
                $_ENV[$k] = $v;
                $_SERVER[$k] = $v;
            } else {
                if (getenv($k) == '') {
                    putenv("$k=" . $v);
                }

                if (!isset($_ENV[$k])) {
                    $_ENV[$k] = $v;
                }

                if (!isset($_SERVER[$k])) {
                    $_SERVER[$k] = $v;
                }
            }
        }
        return true;
    }

    /**
     * @param string $inpKey
     * @param string $prefix
     * @return string
     */
    public function get(string $inpKey, string $prefix = ''): string {
        if (empty($prefix) && !empty($this->params['prefix'])) {
            $prefix = $this->params['prefix'];
        }

        // Let's see if this is two keys actually. APP_ENV,ENV
        if ((strpos($inpKey, ',') !== false) || (strpos($inpKey, '|') !== false)) {
            $multi_keys = preg_split('#\s*[,|]+\s*#si', $inpKey);

            foreach ($multi_keys as $key) {
                $val = $this->get($key, $prefix);
                if (!empty($val)) {
                    return $val;
                }
            }
            return ''; // nothing found
        }

        $key = $this->formatPrefix($inpKey);
        $var_name = $this->formatPrefix($prefix . $key);
        $key_no_pref = str_replace($prefix, '', $key);

        foreach ([ $key, $var_name, $key_no_pref, ] as $v) {
            $v = trim($v, '_-'); // sometimes keys have leading/trailing chars

            // Look for constant
            if (defined($v)) {
                return constant($v);
            }

            // Look for Environment Variable
            if (!empty($_ENV[$v])) {
                return $_ENV[$v];
            }

            // Look for Server Variable
            if (!empty($_SERVER[$v])) {
                return $_SERVER[$v];
            }
            $val = getenv($v);

            if (!empty($val)) {
                return $val;
            }

            // Case-sensitive search in const's. The const must end in the searched keyword.
            $allConst = get_defined_constants();
            $keys = preg_grep(sprintf('#%s$#s', preg_quote($v, '#')), array_keys($allConst));

            if (!empty($keys) && count($keys) == 1) { // one match
                $found_key = array_shift($keys); // id could be anything and not necessarily 0
                return $allConst[$found_key];
            }

            // Partial env match. Trailing
            if (version_compare(phpversion(), '7.1', '>=')) {
                $allEnvs = getenv();
                $keys = preg_grep(sprintf('#%s$#si', preg_quote($v, '#')), array_keys($allEnvs));

                if (!empty($keys) && count($keys) == 1) { // one match
                    $found_key = array_shift($keys); // id could be anything and not necessarily 0
                    return $allEnvs[$found_key];
                }
            }
        }
        return '';
    }

    /**
     * @param string $prefix
     * @param $str
     * @return bool
     */
    public function hasPrefix(string $prefix, $str): bool {
        return !empty( $prefix) && strcasecmp($prefix,substr( $str, 0, strlen($prefix))) == 0;
    }

    /**
     * Defines php const's based on the names. If prefix is passed it will be PREPENDED to each const
     * @param string $prefix
     * @return string
     */
    public function formatPrefix(string $prefix = ''): string {
        $prefix = empty($prefix) ? '' : trim($prefix);
        if (empty($prefix)) {
            return '';
        }

        if (substr($prefix, -1, 1) != '_') { // Let's append underscore automatically if not passed.
            $prefix .= '_';
        }

        $prefix = preg_replace('#\W#si', '_', $prefix);
        $prefix = preg_replace('#_+#si', '_', $prefix);
        return strtoupper($prefix);
    }

    /**
     * Defines php const based on the names. If prefix is passed it will be PREPENDED to each const
     * @param array $data
     * @param string $prefix
     * @return bool
     */
    public function defineConst(array $data = [], string $prefix = ''): bool {
        if (empty($data) || !is_array($data)) {
            return false;
        }

        if (empty($prefix) && !empty($this->params['prefix'])) {
            $prefix = $this->params['prefix'];
        }
        $prefix = $this->formatPrefix($prefix);

        foreach ($data as $k => $v) {
            if (!$this->hasPrefix($prefix, $k)) {
                $k = $prefix . $k;
            }
            if (defined($k)) {
                continue;
            }
            $v = is_scalar($v) ? $v : json_encode($v);
            define($k, $v);
            $this->processed[$k] = $v;
        }
        return true;
    }

    /**
     * Reads the .env file if it finds it. Skips comments and empty lines.
     * The keys are UPPERCASE.
     *
     * @param string $file
     * @return array
     */
    public function read(string $file = ''): array {
        $data = [];
        if (empty($file)) {
            $found = 0;
            $files = [];

            // Does the developer need to start checking from specific folders?
            if (!empty($this->params['scan_dirs'])) {
                $scan_dirs = (array) $this->params['scan_dirs'];

                foreach ($scan_dirs as $scan_dir) {
                    $files[] = rtrim($scan_dir, '/') . '/.env';
                }
            }

            // We're checking 1 devel above doc root
            if ( ! empty( $_SERVER['DOCUMENT_ROOT'] ) ) {
                $files[] = dirname( $_SERVER['DOCUMENT_ROOT'] ) . '/.env';
            }

            if ( defined('ABSPATH') ) { // WordPress set up.
                $files[] = dirname(ABSPATH) . '/.env';
                $files[] = ABSPATH . '/.env';
            }

            if ( ! empty( $_SERVER['DOCUMENT_ROOT'] ) ) {
                $files[] = $_SERVER['DOCUMENT_ROOT'] . '/.env';
            }

            $files[] = __DIR__ . '/.env';

            $files = array_unique($files);

            foreach ($files as $checked_file) {
                if (file_exists($checked_file)) {
                    $file = $checked_file;
                    $found = 1;
                    break;
                }
            }
        }

        if ( empty($file) || empty($found) || ! @file_exists($file) ) { // could produce warnings if outside of open base dir
            return $data;
        }

        $buff = file_get_contents($file, LOCK_SH);
        $lines = explode("\n", $buff);
        $lines = array_map('trim', $lines);
        $lines = array_unique($lines); // no dups
        $lines = array_filter($lines); // rm empty lines

        foreach ($lines as $line) {
            $first_char = substr($line, 0, 1);

            // empty or single line comments
            if (empty($line) || $first_char == '#' || $first_char == ';' || ($first_char == '/' && substr($line, 1, 1) == '/')) {
                continue;
            }

            $eq_pos = strpos($line, '=');

            if ($eq_pos === false) {
                continue;
            }

            $key = substr($line, 0, $eq_pos);
            $key = trim($key, '\'" ');
            $key = strtoupper($key);
            $val = substr($line, $eq_pos + 1);
            $val = str_replace('=', '', $val); // jic
            $pos = strpos($val, '#'); // does the value have a comment ?
            // rm comment from value field if not prefixed by a slash \
            if (($pos !== false) && substr($val, $pos - 1, 1) != "\\" ) {
                $val = substr($val, 0, $pos);
            }
            $val = trim($val, '\'" ');
            $data[$key] = $val;
        }
        return $data;
    }

    /**
     * Loads, updates env and defines php const
     * @param array $inpData
     * @return bool
     */
    public function run(array $inpData = []): bool {
        $data = $this->read();
        $data = array_replace_recursive($inpData, $data);
        if (empty($data)) {
            return false;
        }
        $this->updateEnv( $data );
        $this->defineConst( $data );

        return true;
    }
}