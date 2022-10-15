<?php /** @noinspection PhpMissingFieldTypeInspection */

class Log {
    public static Log $instance;
    private array $params;
    private int $logSize;
    private string $file;
    private $fh;

    protected array $options = [
        // e.g. '/path/to/logfile' OR use '.' = logfile life in same directory
        'path'           => '.',
        // logfile filename without extension ('-date' in Y-m-d format will be attached)
        'filename'       => 'log',
        // e.g. true = use system log function (works only in txt format)
        'syslog'         => false,
        // e.g. 0644 OR 0777 see: http://php.net/manual/en/function.chmod.php
        'filePermission' => 0644,
        // Maximal LogFile Size in MB
        'maxSize'        => 10,
        // e.g. 'txt' = Text with TAB OR 'csv' = Comma-Separated Values with (,) OR 'htm' = HTML
        'format'         => 'htm',
        // e.g. 'terminal' = terminalcss.xyz OR 'barecss' = barecss.com OR plain = simple HTML
        'template'       => 'barecss',
        // e.g. 'UTC' see: http://php.net/manual/en/timezones.php
        'timeZone'       => 'UTC',
        // e.g. 'Y-m-d H:i:s' see: http://php.net/manual/en/function.date.php
        'dateFormat'     => 'Y-m-d H:i:s',
        // e.g. true = Calling Prog. AND Line number OR false = Only calling Prog.
        'backtrace'      => true,
    ];
    // log message severities from RFC 3164, section 4.1.1, table 2.
    // http://www.faqs.org/rfcs/rfc3164.html
    protected array $level = [
        100 => 'DEBUG',     // Debug: debug messages
        200 => 'INFO',      // Informational: informational messages
        300 => 'NOTICE',    // Notice: normal but significant condition
        400 => 'WARNING',   // Warning: warning conditions
        500 => 'ERROR',     // Error: error conditions
        600 => 'CRITICAL',  // Critical: critical conditions
        700 => 'ALERT',     // Alert: action must be taken immediately
        800 => 'EMERGENCY', // Emergency: system is unusable
        900 => 'GAU',       // Maximum Credible Accident: no system anymore, we need no log
    ];
    // set open and write error messages for die()
    protected array $error = [
        'openA' => 'The logfile could not be opened for appending. Check permissions: ',
        'openW' => 'The logfile exists, but could not be opened for writing. Check permissions: ',
        'write' => 'The logfile could not be written. Check logfile: ',
    ];
    // set logfile name and options
    public function __construct($params=[]) {
        if (!isset($paramas['path'])) {
            $params['path'] = env('PATH_LOGS');
        }
        $this->params = array_merge($this->options, $params);
        // set default max logfile size
        $this->maxSize();
    }
    // set logfile max. filesize
    public function maxSize($size=0) {
        if ($size==0) $size=$this->params['maxSize'];
        $this->logSize = $size * (1024 * 1024); // calc megabyte to byte
    }
    // alias functions
    public function debug($message){
        $this->write((string) $message, 100);
    }
    public function info($message){
        $this->write((string) $message, 200);
    }
    public function notice($message){
        $this->write((string) $message, 300);
    }
    public function warning($message){
        $this->write((string) $message, 400);
    }
    public function error($message){
        $this->write((string) $message, 500);
    }
    public function critical($message){
        $this->write((string) $message, 600);
    }
    public function alert($message){
        $this->write((string) $message, 700);
    }
    public function emergency($message){
        $this->write((string) $message, 800);
    }
    public function gau($message){
        $this->write((string) $message, 900);
    }
    // write message to the logfile
    public function write($message, $status) {
        if (is_array($message)) {
            $message = implode(' ', $message);
        }
        // if status is a number set status name
        if (isset($this->level[$status])) {
            $status = $status.' '.$this->level[$status];
        }
        // if file handler doesn't exist, then open logfile
        if (!isset($this->fh) || !is_resource($this->fh)) {
            $this->lopen();
        }
        // use sys log
        if ($this->params['syslog']) {
            $type = 'txt';
            $time = '';
        } else {
            // get logfile type
            $type = strtolower($this->params['format']);
            // define current time for logfile entry - (@ suppress the E_WARNING)
            @ini_set('date.timezone', $this->params['timeZone']); // set timezone
            @date_default_timezone_set($this->params['timeZone']); // set timezone
            $time = @date($this->params['dateFormat']);
        }
        // look for the caller
        if ($this->params['backtrace']) {
            $backtrace = debug_backtrace();
            $caller = array_shift($backtrace);
            $proc = $caller['file'].' ('.$caller['line'].')';
        } else {
            $proc = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        }
        // build logfile entry
        $line = '';
        if ($type=='txt') {
            $line = $time."\t".$proc."\t".$message."\t".$status.PHP_EOL;
        } elseif ($type=='csv') {
            $line = $time.','.$proc.','.$message.','.$status.PHP_EOL;
        } elseif ($type=='htm'||'html') {
            $html1 = '<tr><td data-label="Date/Time">';
            $html2 = '</td><td data-label="Program">';
            $html3 = '</td><td data-label="Message">';
            $html4 = '</td><td data-label="Status">';
            $html5 = '</tag></td></tr>';
            $status= '<tag '.$status.'>'.$status;
            $line  = $html1.$time.$html2.$proc.$html3.$message.$html4.$status.$html5.PHP_EOL;
        }
        // write current entry to the log file
        if ($this->params['syslog']) {
            error_log($line);
        } else {
            flock($this->fh, LOCK_EX);
            fwrite($this->fh, $line) or die($this->error['write'].$this->file);
            flock($this->fh, LOCK_UN);
        }
        // in debug mode print entry also to the browser
        if (env('DEBUG_MODE') === 'On') {
            echo '<pre>'.$line.'</pre>';
        }
    }
    // open logfile (private method)
    private function lopen() {
        // get logfile type
        $type = strtolower($this->params['format']);
        // get logfile path
        $path = rtrim($this->params['path'], '\\/');
        // sys log only in txt format
        if ($this->params['syslog']) {
            $type='txt';
        }
        // get default logfile name
        $this->file = $path .'/'.$this->params['filename'].'-'.date('Y-m-d').'.'.$type;
        // if logfile is too big, delete it
        if (file_exists($this->file) and isset($this->logSize)) {
            clearstatcache(FALSE, $this->file);
            if (filesize($this->file) > $this->logSize) {
                unlink($this->file);
              }
        }
        // use sys log
        if ($this->params['syslog']) {
            @ini_set('log_errors', 1);
            @ini_set('error_log', $this->file);
            return;
        }
        // if logfile not exist create it
        if (!file_exists($this->file)) {
            $this->fh = fopen($this->file, 'w')
                or die($this->error['openW'].$this->file);
            if ($type=='htm' || $type=='html') {
                if (!($html = @file_get_contents('tpl.' . $this->params['template'] . '.htm'))) {
                    $html = '<html lang="en"><head><title></title><body><h2>Log</h2><table>';
                }
                fwrite($this->fh, $html) or die($this->error['write'].$this->file);
            }
            fclose($this->fh);
            if (!is_writable($this->file)) {
                chmod($this->file, $this->params['filePermission']);
            }
        }
        // file exist, so open logfile for appending
        $this->fh = fopen($this->file, 'a')
            or die($this->error['openA'].$this->file);
    }
    // close logfile
    public function __destruct(){
        if ($this->fh) {
            fclose($this->fh);
        } elseif ($this->params['syslog']){
            closelog();
        }
    }

    /**
     * @param string $method | Can be one of the following values: debug,info,notice,warning,error,critical,alert,emergency,gau
     * @param mixed $arguments
     * @return Log
     */
    public static function __callStatic(string $method, $arguments) {
        if (Log::$instance == null) {
            Log::$instance = new static;
        }
        call_user_func([Log::$instance,$method],$arguments);
        return Log::$instance;
    }
}
