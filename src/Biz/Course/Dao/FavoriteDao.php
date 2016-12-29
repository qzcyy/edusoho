<?php

namespace Biz\Course\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface FavoriteDao extends GeneralDaoInterface
{
    public function getByUserIdAndCourseId($userId, $courseId, $type);

    public function searchByUserId($userId, $start, $limit);

    public function countByUserId($userId);
}