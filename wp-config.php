<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do banco de dados
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Configurações do banco de dados - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'ArteComics' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'j}ZFS!_gHhAYSKlr!?VD9([ZMDt/D}}Q2@i~%~mkB/I{ATv3YI3Cou0~ 9GgS4:o' );
define( 'SECURE_AUTH_KEY',  'VDzDh@Xn*!7w)w<dDTjix664/5Mn!$Qr4Hx5Y2,Eo:O_xn+K#iY5LkujcxT#A7C5' );
define( 'LOGGED_IN_KEY',    'I4ZDJdYMUei6/#v&wXPLu`]6OOm<!}+Em {W&0u9^4VzZVw-Z4,5gLVmRv|y%DFU' );
define( 'NONCE_KEY',        '7/53ws<YyFrKYQ0g1@8lpxuv:{2A8cFK+S~K=L(=mz4T8mNr83lW9WfC,(wDi$J)' );
define( 'AUTH_SALT',        'd7Q>{%ds50[$?<[*$ZmaT;|{>j~#=mUc0b2AOX-t2 ,bILWd`&d5.{E6gg&wum:_' );
define( 'SECURE_AUTH_SALT', 'I`SKl7[_^m/q?vf[(!k|!gr}8]_EiTu2oe-S6KP|PqZ#4Dj_qZvC<!EfLHpLgB!y' );
define( 'LOGGED_IN_SALT',   'EK2760HHuP2Z)#dmD rqZc|jZI|J?/6(RGT4`3dgrtZ29HBWq-&7XsJ4,YO7R_!G' );
define( 'NONCE_SALT',       'J~b>R,sSY1I[.n4juoom;(EX-bV@Tk^BK~R nu6<T#Q./)DGr>Ym@?4YPH+hu*#r' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'cp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Adicione valores personalizados entre esta linha até "Isto é tudo". */



/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once ABSPATH . 'wp-settings.php';
