<?php
/**
 * The following variables are available in this template:
 * @var $this CrudCode
 */

echo "<?php\n";
echo "/**\n";
echo " * @var \$this " . $this->controllerClass . "\n";
echo " * @var \$" . lcfirst($this->modelClass) . " " . $this->modelClass . "\n";
echo " */\n";
echo "\n";
echo "\$this->pageTitle = \$this->pageHeading = \$" . lcfirst($this->modelClass) . "->getName() . ' - ' . \$this->getName() . ' ' . t('Log');\n";
echo "\n";
echo "\$this->breadcrumbs = array();\n";
echo "\$this->breadcrumbs[\$this->getName() . ' ' . t('List')] = user()->getState('index." . lcfirst($this->modelClass) . "', array('/" . lcfirst($this->modelClass) . "/index'));\n";
echo "\$this->breadcrumbs[\$" . lcfirst($this->modelClass) . "->getName()] = \$" . lcfirst($this->modelClass) . "->getLink();\n";
echo "\$this->breadcrumbs[] = t('Log');\n";
echo "\n";
echo "\$this->renderPartial('_menu', array(\n";
echo "    '" . lcfirst($this->modelClass) . "' => \$" . lcfirst($this->modelClass) . ",\n";
echo "));\n";
echo "\$this->renderPartial('/auditTrail/_log', array(\n";
echo "    'model' => \$" . lcfirst($this->modelClass) . ",\n";
echo "));\n";