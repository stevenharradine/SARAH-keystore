<?php
	require_once '../../views/_secureHead.php';
	require_once $relative_base_path . 'models/edit.php';

	if( isset ($sessionManager) && $sessionManager->isAuthorized () ) {
		$KEYMAN_ID = request_isset ('id');
		
		$record = KeyManager::getRecord ($KEYMAN_ID, $USER_ID);

		$app_title = 'Edit | ' . $app_title;

		// build edit view
		$editModel = new EditModel ('Edit', 'update_by_id', $KEYMAN_ID);
		$editModel->addRow ('name', 'Name', $record->getName () );
		$editModel->addTextarea ('private_key', 'Private key', $record->getPrivateKey () );
		$editModel->addTextarea ('public_key', 'Public key', $record->getPublicKey () );
		$editModel->addRow ('passphrase', 'Passphrase', $record->getPassphrase () );

		$views_to_load = array();
		$views_to_load[] = ' ' . EditView2::render($editModel);

		include $relative_base_path . 'views/_generic.php';
	}
?>