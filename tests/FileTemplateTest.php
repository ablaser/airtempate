<?php

	use PHPUnit\Framework\TestCase;
	use org\bovigo\vfs\vfsStream;
	use org\bovigo\vfs\vfsStreamDirectory;
	use AirTemplate\FileTemplate;

	if (!defined('n')) define('n', "\n");
	if (!defined('br')) define('br', "<br>");

	class FileTemplateTest extends TestCase
	{

		private $source_templates = array(
			'test_1' => '<b>{{var1}}</b>',
			'test_2' => '<em>{{var2}}</em>',
		);

		private $root = '';

		private $file_1;

		private $file_2;

		private $file_compiled;

		protected function setUp()
		{
			$this->root = vfsStream::setup('unittestdir');
			$this->file_1 = vfsStream::url('unittestdir/test_1.tmpl');
			$this->file_2 = vfsStream::url('unittestdir/test_2.tmpl');
			$this->file_compiled = vfsStream::url('unittestdir/test.json');

			$bytes1 = file_put_contents($this->file_1, $this->source_templates['test_1']);
			$bytes2 = file_put_contents($this->file_2, $this->source_templates['test_2']);

		}

		public function testFileTemplate()
		{

			// Act
			$engine = new FileTemplate;
			$engine
				->loadTemplates(array( $this->file_1 ))
				->loadTemplates(array( $this->file_2 ), '', true)
				->saveParsedTemplates($this->file_compiled);

			$engine2 = new FileTemplate;
			$engine2->loadParsedTemplates($this->file_compiled);
			$parsedTemplates = $engine2->getParsedTemplates();

			// Assert
			$this->assertTrue(is_array($parsedTemplates));
			$this->assertEquals(2, count($parsedTemplates));
			$this->assertEquals('<b>', $parsedTemplates['test_1.tmpl'][0]);
			$this->assertEquals('var2', $parsedTemplates['test_2.tmpl'][1][0]);

		}


	}