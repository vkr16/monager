<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;
$config['mailtype'] = 'html';

$config['smtp_host'] = 'mail.akuonline.my.id';
$config['smtp_user'] = WEBSERVICE_MAIL_ADDR;
$config['smtp_pass'] = WEBSERVICE_MAIL_PASSWD;
$config['smtp_port'] = 465;
$config['smtp_crypto'] = 'ssl';
