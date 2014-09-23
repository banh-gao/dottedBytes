<?php
	use dottedBytes\libs\configuration\Configuration;
	Configuration::setValue ( 'system.email.smtp.host', '' );
	Configuration::setValue ( 'system.email.smtp.port', '' );
	Configuration::setValue ( 'system.email.smtp.enable', '' );
	Configuration::setValue ( 'system.email.smtp.useAuth', '' );
	Configuration::setValue ( 'system.email.smtp.username', '' );
	Configuration::setValue ( 'system.email.smtp.password', '' );
	Configuration::setValue ( 'system.email.fromMail', 'user@myHost.com' );
	Configuration::setValue ( 'system.email.replyMail', 'user@myHost.com' );
	Configuration::setValue ( 'system.site.name', 'DottedBytes' );
	Configuration::setValue ( 'system.site.description', '' );
	Configuration::setValue ( 'system.site.keywords', '' );
	Configuration::setValue ( 'system.site.template', 'basic' );
	Configuration::setValue ( 'system.site.dateFormat', '%A %e %B %Y' );
	Configuration::setValue ( 'system.site.timeFormat', '%H:%M:%S' );
	Configuration::setValue ( 'system.site.languageCode', 'it' );
	Configuration::setValue ( 'system.module.default', 'contentMgr' );
	Configuration::setValue ( 'system.users.atteptControl.enable', 'false' );
	Configuration::setValue ( 'system.users.atteptControl.maxAttepts', 3 );
	Configuration::setValue ( 'system.users.atteptControl.retryingDelay', 60 );
	Configuration::setValue ( 'system.users.sessionExpire', 60 );
	
	Configuration::setValue ( 'module.contentMgr.comments.enable', 'true' );
	Configuration::setValue ( 'module.contentMgr.comments.enableGuestPost', 'true' );
	Configuration::setValue ( 'module.contentMgr.category.previewLength', '100' );
	Configuration::setValue ( 'module.contentMgr.category.columns', '2' );
	Configuration::setValue ( 'module.contentMgr.category.pageElements', '5' );
	Configuration::setValue ( 'module.contentMgr.category.popularArticles', '10' );
	Configuration::setValue ( 'module.contentMgr.news.columns', '2' );
	Configuration::setValue ( 'module.contentMgr.search.previewLength', '100' );
	
	Configuration::setValue ( 'module.siteConfig.gAnalytics.enable', 'true' );
	Configuration::setValue ( 'module.siteConfig.gAnalytics.account', '' );
	
	Configuration::setValue ( 'module.gallery.images.baseDir', 'images' );
	Configuration::setValue ( 'module.gallery.images.allowedTypes', 'image/jpg,image/gif,image/jpeg' );
	Configuration::setValue ( 'module.gallery.images.thumbHeight', '200' );
	Configuration::setValue ( 'module.gallery.images.thumbWidth', '200' );
	Configuration::setValue ( 'module.gallery.images.maxWidth', '800' );
	Configuration::setValue ( 'module.gallery.pageElements', '10' );
	
	Configuration::setValue ( 'module.userManager.account.enableOpenID', 'false' );
	Configuration::setValue ( 'module.userManager.admin.googleKey', '' );
	Configuration::setValue ( 'module.userManager.registration.enable', 'true' );
	Configuration::setValue ( 'module.userManager.registration.enableRecovery', 'true' );
	Configuration::setValue ( 'module.userManager.registration.uniqueMail', 'false' );
	Configuration::setValue ( 'module.userManager.pm.enable', 'true' );
	Configuration::setValue ( 'module.userManager.pm.multipleLimit', '5' );
	
	Configuration::setValue ( 'module.userManager.showUsersInfo', 'true' );
	Configuration::setValue ( 'module.userManager.showConnectedUsers', 'true' );
?>