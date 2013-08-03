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
echo "// index\n";
echo "if (\$this->action->id == 'index') {\n";
echo "    \$menu = NavbarItems::topMenu();\n";
echo "    \$this->menu = \$menu['items'];\n";
echo "    return; // no more links\n";
echo "}\n";
echo "\n";
echo "// create\n";
echo "if (\$" . lcfirst($this->modelClass) . "->isNewRecord) {\n";
echo "    \$this->menu[] = array(\n";
echo "        'label' => t('Create'),\n";
echo "        'url' => array('/" . lcfirst($this->modelClass) . "/create'),\n";
echo "    );\n";
echo "    return; // no more links\n";
echo "}\n";
echo "\n";
echo "// view\n";
echo "\$this->menu[] = array(\n";
echo "    'label' => t('View'),\n";
echo "    'url' => \$" . lcfirst($this->modelClass) . "->getUrl(),\n";
echo ");\n";
echo "\n";
echo "// others\n";
echo "foreach (\$" . lcfirst($this->modelClass) . "->getDropdownLinkItems() as \$linkItem) {\n";
echo "    \$this->menu[] = \$linkItem;\n";
echo "}\n";
echo "\n";
echo "// more\n";
echo "\$more = array();\n";
echo "foreach (\$" . lcfirst($this->modelClass) . "->getMoreDropdownLinkItems() as \$linkItem) {\n";
echo "    \$more[] = \$linkItem;\n";
echo "}\n";
echo "if (\$more) {\n";
echo "    \$this->menu[] = array(\n";
echo "        'label' => t('More'),\n";
echo "        'items' => \$more,\n";
echo "    );\n";
echo "}\n";

