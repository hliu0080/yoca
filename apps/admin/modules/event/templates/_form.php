<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('event/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" class="form-horizontal" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
	<?php if (!$form->getObject()->isNew()): ?>
		<input type="hidden" name="sf_method" value="put" />
	<?php endif; ?>

	<fieldset>
		<?php echo $form ?>
		<div class="form-actions">
			<input type="submit" value="<?php print $form->getObject()->isNew()?'Create':'Update'?>" class="btn btn-wuxia btn-primary" />
		</div>
	</fieldset>
</form>
