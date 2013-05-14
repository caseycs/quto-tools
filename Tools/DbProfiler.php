<?php

namespace Tools;

class DbProfiler extends \Zend_Db_Profiler
{
	/**
	 * @var \Zend_Log
	 */
	private $log;

	/**
	 * counter of the total elapsed time
	 * @var double
	 */
	private $totalElapsedTime;

	/**
	 * @param bool $enabled
	 */
	public function __construct($enabled = false)
	{
		parent::__construct($enabled);

		$this->log = new \Zend_Log();
		$this->log->addWriter(new \Zend_Log_Writer_Stream(PATH . 'data/p/db-query-log.log'));
	}

	/**
	 * Intercept the query end and log the profiling data.
	 *
	 * @param int $queryId
	 */
	public function queryEnd($queryId)
	{
		$state = parent::queryEnd($queryId);

		if (!$this->getEnabled() || $state == self::IGNORED) {
			return;
		}

		// get profile of the current query
		$profile = $this->getQueryProfile($queryId);

		// update totalElapsedTime counter
		$this->totalElapsedTime += $profile->getElapsedSecs();

		// create the message to be logged
		$message = "\r\nElapsed Secs: " . round($profile->getElapsedSecs(), 5) . "\r\n";
		$message .= "Query: " . $profile->getQuery() . "\r\n";

		// log the message as INFO message
		$this->log->info($message);
	}
}