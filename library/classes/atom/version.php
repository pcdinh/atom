<?php

/**
 * Core Atom library namespace. This namespace contains all of the fundamental
 * components of the Atom framework, plus additional utilities that are
 * provided by default. Some of these default components have sub namespaces
 * if they provide children objects.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom;

/**
 * Versioning class, contains all of the Atom versioning values
 *
 * @package    Atom
 * @subpackage Library
 */
class Version {

	/**
	 * Atom simple version, this contains the current release in the form of:
	 *
	 * major.minor.release
	 *
	 * For example, 1.0, 1.0.1, etc.
	 *
	 * @var     string
	 */
	const SIMPLE = '1.0.0';

	/**
	 * Major version number
	 *
	 * @var     integer
	 */
	const MAJOR = 1;

	/**
	 * Minor version number
	 *
	 * @var     integer
	 */
	const MINOR = 1;

	/**
	 * Release version number
	 *
	 * @var     integer
	 */
	const RELEASE = 0;

	/**
	 * Atom version ID, this contains the version id in the form of:
	 *
	 * id = (major_version * 10000) + (minor_version * 100) + release_version
	 *
	 * 1.0.0     10000
	 * 1.1.0     10100
	 * 1.2.2     10202
	 *
	 * @var     integer
	 */
	const ID = 10000;

	/**
	 * Development preview mode, this is set to true if this is a development
	 * preview release, like an Alpha, Beta or Release Candidate
	 *
	 * @var     boolean
	 */
	const PREVIEW = true;

	/**
	 * Development preview type, this is set to the preview type, like 'Alpha',
	 * 'Beta' or 'Release Candidate' if this is a preview release.
	 *
	 * @var     string
	 */
	const PREVIEW_TYPE = 'Alpha';

	/**
	 * Development preview number, this is set to the preview number for the
	 * current preview type. This is only set if this is a preview release.
	 *
	 * @var     integer
	 */
	const PREVIEW_NUMBER = 1;

	/**
	 * Atom version string, this is the full version string, which includes the
	 * pre-release name, version nad the version number of the upcoming version
	 * if pre-release. For example:
	 *
	 * 1.0.0 Alpha 1
	 * 1.0.3 Release Candidate 2
	 * 1.0.4
	 *
	 * @var     string
	 */
	const FULL = '1.0.0 (Alpha 1)';
}

/* End of file version.php */