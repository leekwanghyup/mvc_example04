<?php

use app\core\form\Form;
?>

<h1>Register</h1>
<?php $form = Form::begin('/register','post') ?>
<table>
<?php echo $form->field($model,'firstname','First Name'); ?>
<?php echo $form->field($model,'lastname','Last Name'); ?>
<?php echo $form->field($model,'email','Email')->emailField(); ?>
<?php echo $form->field($model,'password','Password')->passwordField(); ?>
<?php echo $form->field($model,'confirmPassword','Confirm Password')->passwordField(); ?>
</table>
<button>Submit</button>
<?php $form->end() ?>

<style>
.is-invalid { border:  1px solid red;}
.invalid-feedback { font-size: 12px; color: red; }
</style>