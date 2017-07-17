<?php

namespace Biz\Course\Component\Clones\Chain;

use Biz\Course\Component\Clones\AbstractClone;
use Codeages\Biz\Framework\Context\Biz;

class CourseClone extends AbstractClone
{
    public function __construct(Biz $biz, array $processNodes = array(), $auto = false)
    {
        $processNodes = array(
            'course-member' => array(
                'class' => 'Biz\Course\Component\Clones\Chain\CourseMemberClone',
                'priority' => 100,
            ),
            'course-question' => array(
            ),
        );
        parent::__construct($biz, $processNodes, $auto);
    }

    protected function cloneEntity($source, $options)
    {
        return $this->doCourseCloneProcess($options, $options);
    }

    private function doCourseCloneProcess($options, $options)
    {
    }

    protected function getFields()
    {
        // TODO: Implement getFields() method.
    }
}
