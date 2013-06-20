<?php
Yii::import('bootstrap.widgets.BootNavbar');

class Navbar extends BootNavbar
{
    /**
     * @var array navigation items that cannot be collapsed.
     */
    public $constantItems = array();

    /**
     * Runs the widget.
     */
    public function run()
    {
        $classes = array('navbar');

        if ($this->fixed !== false) {
            $validFixes = array(self::FIXED_TOP, self::FIXED_BOTTOM);
            if (in_array($this->fixed, $validFixes))
                $classes[] = 'navbar-fixed-' . $this->fixed;
        }

        $this->htmlOptions['id'] = $this->id;

        $classes = implode(' ', $classes);
        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] .= ' ' . $classes;
        else
            $this->htmlOptions['class'] = $classes;

        $this->brandOptions['id'] = 'brand';

        $containerCssClass = $this->fluid ? 'container-fluid' : 'container';

        echo CHtml::openTag('div', $this->htmlOptions);
        echo '<div class="navbar-inner"><div class="' . $containerCssClass . '">';

        if ($this->collapse) {
            echo '<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">';
            echo '<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>';
            echo '</a>';
        }

        foreach ($this->constantItems as $item)
        {
            if (is_string($item))
                echo $item;
            else
            {
                if (isset($item['class'])) {
                    $className = $item['class'];
                    unset($item['class']);

                    $this->controller->widget($className, $item);
                }
            }
        }

        if ($this->brand !== false)
            echo CHtml::openTag('a', $this->brandOptions) . $this->brand . '</a>';

        if ($this->collapse)
            echo '<div class="nav-collapse">';

        foreach ($this->items as $item)
        {
            if (is_string($item))
                echo $item;
            else
            {
                if (isset($item['class'])) {
                    $className = $item['class'];
                    unset($item['class']);

                    $this->controller->widget($className, $item);
                }
            }
        }

        if ($this->collapse)
            echo '</div>';

        echo '</div></div></div>';
    }
}
