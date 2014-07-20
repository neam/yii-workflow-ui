requirements

composer.json


"neam/yii-item": "dev-develop",

package.json

"grunt": "^0.4.1",
"grunt-contrib-less": "~0.6.4",
"grunt-cli": "^0.1.13",
"grunt-contrib-watch": "^0.6.1",
"grunt-kss": "^0.2.6"


yii config (at end of main.php)

require($applicationDirectory . '/../vendor/neam/yii-workflow-ui/config/yii-workflow-ui.php');
