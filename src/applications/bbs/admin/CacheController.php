<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * 后台菜单管理操作类
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-10-21
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: CacheController.php 21087 2012-11-27 08:41:03Z long.shi $
 * @package admin
 * @subpackage controller
 */

class CacheController extends AdminBaseController {

	public function run() {}

	public function dorunAction() {
		Wekit::load('cache.srv.PwCacheUpdateService')->updateAll();
		$this->showMessage('success');
	}

	public function doforumAction() {
		Wekit::load('forum.srv.PwForumMiscService')->countAllForumStatistics();
		$this->showMessage('success');
	}

	/**
	 * css压缩
	 */
	public function buildCssAction() {
		$debug = Wekit::C('site', 'css.compress');
		// 当前状态开启，则关闭它
		if ($debug) {
			$debug = 0;
		} else {
			$this->_compressCss();
			$debug = 1;
		}
		
		Wekit::load('config.PwConfig')->setConfig('site', 'css.compress', $debug);
		$this->showMessage('success');
	}

	/**
	 * 更新css缓存
	 */
	public function doCssAction() {
		$this->_compressCss();
		$this->showMessage('success');
	}

	/**
	 * 更新hook缓存
	 */
	public function doHookAction() {
		$r = Wekit::load('hook.srv.PwHookRefresh')->refresh();
		if ($r instanceof PwError) $this->showError($r->getError());
		$this->showMessage('success');
	}

	public function doTplAction() {
		Wekit::load('domain.srv.PwDomainService')->refreshTplCache();
		$this->showMessage('success');
	}

	private function _compressCss() {
		Wind::import('APPS:appcenter.service.srv.helper.PwSystemHelper');
		$writable = PwSystemHelper::checkWriteAble(Wind::getRealDir('THEMES:') . '/');
		if (!$writable) $this->showError(
			'STYLE:style.css.write.fail');
		Wind::import('LIB:compile.compiler.PwCssCompress');
		$compress = new PwCssCompress();
		$r = $compress->doCompile();
		if ($r instanceof PwError) $this->showError($r->getError());
	}
}
?>