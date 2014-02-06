CHANGELOG V 1.0.x

# Batch extraction from itkg library implies some changes

* Constant change (Itkg::$config => Itkg\Batch::$config)
* BATCH_CONFIGURATION => CONFIGURATION - Ex : Itkg\Batch::$config['CONFIGURATION'] = 'MyConfigClass';
* Itkg\Component\Console\Configuration => Itkg\Batch\Component\Console\Configuration
* console_include.php is in vendor/itkg/batch/app directory