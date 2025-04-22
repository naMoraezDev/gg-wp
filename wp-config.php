<?php
define( 'DB_NAME', getenv('WORDPRESS_DB_NAME') ?: 'wpdb' );
define( 'DB_USER', getenv('WORDPRESS_DB_USER') ?: 'wpuser' );
define( 'DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') ?: 'wppassword' );
define( 'DB_HOST', getenv('WORDPRESS_DB_HOST') ?: 'db' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

define('AUTH_KEY',         'd.j^J`PnBEt}qZjhGASTV2#Z|(oGCoLB[^#D~9XmMs<Sb9+s@$m8*R:{S9pc(Mn>');
define('SECURE_AUTH_KEY',  'h~78K1I-Eqj{Ub^RFIM`T@#1^3# 3B72<lm7ptPYrpXs}|U-TdU54UVcP2&#28nt');
define('LOGGED_IN_KEY',    '-{QZ)VtH9Y(%EhrSb4iw=2``yc`&^^pzz`dwA0/FN<Lc=BC,c<S7-J{DZStr,h_v');
define('NONCE_KEY',        '7bn<qkNHz9$sIw^BpC.0w[Y$1F4jo*;<.3w`FIzI8:VX}hx3*!3N)ASMD2 JI>6s');
define('AUTH_SALT',        '<M|s7H9Ki`P7np<1aG~;)6|R2crkCqAXyC6hX-n0V{p?}:vE>&k.Q F8uV5,PRf+');
define('SECURE_AUTH_SALT', '|mVQHkj;I;SCnBIf Q8%.#8G^@4SL(fF):GuJE]v+qi#?+XJ;5B-~:6-D^#fPg&E');
define('LOGGED_IN_SALT',   'HcW{RZNaEp,.8:In{2)]6Tspy!<{Gy-wmzgzzfl2(x[3,T`Nb]?,](:f~&d~(-4E');
define('NONCE_SALT',       'w($+VGTsGS:sGcL+Y%7#u}r+#$Of^:aLQv>5up>zutz3}}E;qdv[1xh9JyNX/`tS');

define('WP_STATELESS_MEDIA_MODE', 'stateless');
define('WP_STATLESS_MEDIA_BUCKET', getenv('WP_STATLESS_GCS_BUCKET_NAME'));
define('WP_STATLESS_MEDIA_KEY_FILE', getenv('WP_STATLESS_GCS_KEY_FILE'));
define('WP_STATELESS_MEDIA_ROOT_DIR', 'wp-content/uploads');
define('WP_STATELESS_MEDIA_CREATE_BUCKET', false);
define('WP_STATELESS_MEDIA_CACHE_CONTROL', 'public,max-age=3600');
define('WP_STATLESS_UPLOAD_LOCAL', true); 

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
  $_SERVER['HTTPS'] = 'on';
}
require_once ABSPATH . 'wp-settings.php';
