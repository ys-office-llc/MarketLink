<?php 
class UseResearchAnalysisArchiveModel extends ResearchModel
{
  public function create($param)
  {
    return parent::create($this->resolve(), $param);
  }

  public function update($param)
  {
    $where = array();

    $where[] = 'id = :id';
    $where[] = 'user_id = :user_id';

    parent::update($this->resolve(), $param, $where);
  }

  public function delete($param)
  {
    parent::delete($this->resolve(), $param);
  }

  public function deleteByParameters($param)
  {
    parent::deleteByParameters($this->resolve(), $param);
  }

  public function gets($user_id, $today)
  {
    return parent::gets($this->resolve(), $user_id);
  }

  public function getsByDate($user_id, $today)
  {
    $param = array();
    $where = array();

    $param['created_at'] = $today;
    $param['user_id']    = $user_id;
    $param['deleted']    = 0;

    $where[] = 'created_at = :created_at';
    $where[] = 'user_id    = :user_id';
    $where[] = 'deleted    = :deleted';

    return parent::getsByParameters($this->resolve(), $param, $where);
  }

  public function getsByResearchAnalysisId($user_id, $id)
  {
    $param = array();
    $where = array();

    $param['research_analysis_id'] = $id;
    $param['user_id']              = $user_id;
    $param['deleted']              = 0;

    $where[] = 'research_analysis_id = :research_analysis_id';
    $where[] = 'user_id              = :user_id';
    $where[] = 'deleted              = :deleted';

    return parent::getsByParameters($this->resolve(), $param, $where);
  }

  public function getsByResearchAnalysisId1Y1M($user_id, $id, $year, $month)
  {
    $param = array();
    $where = array();
    $ym    = null;

    $param['research_analysis_id'] = $id;
    $param['user_id']              = $user_id;
    $param['deleted']              = 0;

    $ym = $year.$month;

    $where[] = 'research_analysis_id = :research_analysis_id';
    $where[] = 'user_id              = :user_id';
    $where[] = 'deleted              = :deleted';

    return parent::getsByParameters1Y1M($this->resolve(), $ym, $param, $where);
  }

  public function getsByResearchAnalysisIdYMtoYM(
    $user_id,
    $id,
    $year_current,
    $month_current,
    $year_past,
    $month_past
  )
  {
    $param = array();
    $where = array();
    $ym_c  = null;
    $ym_p  = null;

    $param['research_analysis_id'] = $id;
    $param['user_id']              = $user_id;
    $param['deleted']              = 0;

    $ym_c = $year_current.$month_current;
    $ym_p = $year_past.$month_past;

    $where[] = 'research_analysis_id = :research_analysis_id';
    $where[] = 'user_id              = :user_id';
    $where[] = 'deleted              = :deleted';

    return parent::getsByParametersYMtoYM(
      $this->resolve(),
      $ym_c,
      $ym_p,
      $param,
      $where
    );
  }

  public function getsByResearchAnalysisId1Y($user_id, $id, $year)
  {
    $param = array();
    $where = array();
    $y     = null;

    $param['research_analysis_id'] = $id;
    $param['user_id']              = $user_id;
    $param['deleted']              = 0;

    $y = $year;

    $where[] = 'research_analysis_id = :research_analysis_id';
    $where[] = 'user_id              = :user_id';
    $where[] = 'deleted              = :deleted';

    return parent::getsByParameters1Y($this->resolve(), $y, $param, $where);
  }

  public function get($user_id, $id)
  {
    return parent::get($this->resolve(), $user_id, $id);
  }

  public function desc()
  {
    return parent::desc($this->resolve());
  }

  public function getTableValues()
  {
    return parent::getTableValues($this->resolve());
  }

  public function counter($user_id)
  {
    $param = array();
    $where = array();
    $param['user_id'] = $user_id;
    $param['deleted'] = 0;
    $where[] = 'user_id = :user_id';
    $where[] = 'deleted = :deleted';

    return parent::counter($this->resolve(), $param, $where);
  }

  public function counterCurrentDate($user_id)
  {
    $param = array();
    $where = array();
    $param['user_id'] = $user_id;
    $param['deleted'] = 0;
    $where[] = 'user_id = :user_id';
    $where[] = 'deleted = :deleted';

    return parent::counterCurrentDate($this->resolve(), $param, $where);
  }

}
