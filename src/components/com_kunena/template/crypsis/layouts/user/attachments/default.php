<?php
/**
 * Kunena Component
 * @package         Kunena.Template.Crypsis
 * @subpackage      Layout.User
 *
 * @copyright       Copyright (C) 2008 - 2018 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('behavior.core');

// @var array|KunenaAttachment[] $attachments

$attachments = $this->attachments;
?>
<h3>
	<?php echo $this->headerText; ?>
</h3>

<form action="<?php echo KunenaRoute::_('index.php?option=com_kunena&view=user'); ?>" method="post" id="adminForm"
      name="adminForm">
	<input type="hidden" name="task" value="delfile"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo HTMLHelper::_('form.token'); ?>

	<table class="table table-bordered table-striped table-hover">
		<thead>
		<tr>
			<th class="span1 center">
				#
			</th>
			<th class="span1 center">
				<label>
					<input type="checkbox" name="checkall-toggle" value="cid"
					       title="<?php echo JText::_('COM_KUNENA_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
				</label>
			</th>
			<th class="span1 center">
				<?php echo JText::_('COM_KUNENA_FILETYPE'); ?>
			</th>
			<th class="span2">
				<?php echo JText::_('COM_KUNENA_FILENAME'); ?>
			</th>
			<th class="span2">
				<?php echo JText::_('COM_KUNENA_FILESIZE'); ?>
			</th>
			<th class="span2">
				<?php echo JText::_('COM_KUNENA_ATTACHMENT_MANAGER_TOPIC'); ?>
			</th>
			<th class="span1 center">
				<?php echo JText::_('COM_KUNENA_PREVIEW'); ?>
			</th>
			<th class="span1 center">
				<?php echo JText::_('COM_KUNENA_DELETE'); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php if (!$attachments) : ?>
			<tr>
				<td colspan="8">
					<?php echo JText::_('COM_KUNENA_USER_NO_ATTACHMENTS'); ?>
				</td>
			</tr>
		<?php else :
			$i = $this->pagination->limitstart;
			foreach ($attachments as $attachment) :
				$message = $attachment->getMessage();
				$canDelete = $attachment->isAuthorised('delete');
				?>
				<tr>
					<td class="center"><?php echo ++$i; ?></td>
					<td class="center">
						<?php if ($canDelete)
						{
							echo HTMLHelper::_('grid.id', $i, intval($attachment->id));
						} ?>
					</td>
					<td class="center">
						<?php echo $attachment->isImage() ? KunenaIcons::picture() : KunenaIcons::file(); ?>
					</td>
					<td>
						<?php echo $attachment->getShortName(10, 5); ?>
					</td>
					<td>
						<?php echo number_format(intval($attachment->size) / 1024, 0, '', ',') . ' ' . JText::_('COM_KUNENA_USER_ATTACHMENT_FILE_WEIGHT'); ?>
					</td>
					<td>
						<?php echo $this->getTopicLink($message->getTopic(), $message, null, null, '', null, false, true); ?>
					</td>
					<td class="center">
						<?php echo $attachment->getLayout()->render('thumbnail'); ?>
					</td>
					<td class="center">

						<?php if ($canDelete) : ?>
							<a href="#modaldelete<?php echo $i ?>" role="button" class="btn center" data-toggle="modal"><?php echo KunenaIcons::delete(); ?></a>

							<div id="modaldelete<?php echo $i ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modaldeleteLabel" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="modaldeleteLabel"><?php echo JText::_('COM_KUNENA_FILES_CONFIRMATION_DELETE_MODAL_LABEL') ?></h3>
								</div>
								<div class="modal-body">
									<p><?php echo JText::sprintf('COM_KUNENA_FILES_DELETE_MODAL_DESCRIPTION', $attachment->getFilename(), number_format(intval($attachment->size) / 1024, 0, '', ',') . ' ' . JText::_('COM_KUNENA_USER_ATTACHMENT_FILE_WEIGHT')); ?></p>
								</div>
								<div class="modal-footer">
									<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_KUNENA_FILES_CANCEL_DELETE_MODAL_BUTTON') ?></button>
									<button class="btn btn-primary" onclick="return listItemTask('cb<?php echo $i; ?>','delfile');"><?php echo JText::_('COM_KUNENA_FILES_CONFIRM_DELETE_MODAL_BUTTON') ?></button>
								</div>
							</div>
						<?php endif; ?>

					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	<div class="pull-left">
		<?php echo $this->subLayout('Widget/Pagination/List')
			->set('pagination', $this->pagination->setDisplayedPages(4))
			->set('display', true); ?>
	</div>

	<?php if ($attachments) : ?>
		<a href="#modaldeleteall" class="btn pull-right" data-toggle="modal"><?php echo JText::_('COM_KUNENA_FILES_DELETE'); ?></a>

		<div id="modaldeleteall" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modaldeleteallLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="modaldeleteLabel"><?php echo JText::_('COM_KUNENA_FILES_CONFIRMATION_DELETE_SELECTED_MODAL_LABEL') ?></h3>
			</div>
			<div class="modal-body">
				<p><?php echo JText::_('COM_KUNENA_FILES_DELETE_SELECTED_MODAL_DESCRIPTION'); ?></p>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_KUNENA_FILES_CANCEL_DELETE_MODAL_BUTTON') ?></button>
				<button class="btn btn-primary" onclick="adminform.submit();"><?php echo JText::_('COM_KUNENA_FILES_CONFIRM_DELETE_SELECTED_MODAL_BUTTON') ?></button>
			</div>
		</div>
	<?php endif; ?>
</form>
