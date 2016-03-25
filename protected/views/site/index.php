<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>
<pre>
    <?php
    //    echo ;
    print_r(TheMovieDB::test());
    ?>
    </pre>

</p>

