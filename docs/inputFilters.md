# Input Filters

> PHP 7.4 Supports the following filters

- "int",
- "boolean",
- "float",
- "validate_regexp",
- "validate_domain",
- "validate_url",
- "validate_email",
- "validate_ip",
- "validate_mac",
- "string",
- "stripped",
- "encoded",
- "special_chars",
- "full_special_chars",
- "unsafe_raw",
- "email",
- "url",
- "number_int",
- "number_float",
- "magic_quotes",
- "add_slashes",
- "callback"

## Usage

- They are still adding to this list with every release of PHP, and I think these are very handy, you can also use the filters together on one field. 
- I Don't know many people who feel comfortable creating regular expressions, and there are some risk in using regular expressions.They can be made to go into a repetitive loop, and in doing so take a lot of computing away from where it is needed.
- So I am using the filters where I can and regex where I have to


