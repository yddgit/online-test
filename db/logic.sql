SELECT DATE_FORMAT(CURRENT_TIMESTAMP,'%Y-%m-%d %H:%i:%s');

SELECT
 t1.id,
 t1.identity_card,
 t1.`name`,
 t1.dept_id,
 t2.`name` AS dept_name,
 t1.org_name,
 t1.is_test
FROM m_user AS t1
LEFT JOIN m_dept t2 ON t1.dept_id = t2.id
WHERE t1.identity_card = '#identity_card'
LIMIT 1;

/*查看用户是否已答过题*/
SELECT count(*) FROM m_user AS t1 WHERE t1.identity_card = '#identity_card' AND t1.is_test = 1;

/*如果用户已答过题，显示分数*/
SELECT t1.id, t2.name, t4.paper_name, t1.score, DATE_FORMAT(t1.test_date,'%Y-%m-%d %H:%i:%s'), t2.org_name, t3.name AS dept_name FROM t_score AS t1
LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
LEFT JOIN m_dept AS t3 ON t2.dept_id = t3.id
LEFT JOIN m_test_paper AS t4 ON t1.test_paper_id = t4.id
WHERE t2.identity_card = '#identity_card'

/*如果用户未答过题，则检查用户是否录入过信息*/

/*如未录入，则先录入信息*/
SELECT count(*) FROM m_user AS t1 WHERE t1.identity_card = '#identity_card';
INSERT INTO m_user (name, identity_card, org_name, dept_id, is_test) VALUES ('#name', '#identity_card', '#org_name', 'dept_id', 0);

/*抽取试卷进行答题*/
/*执行以下SQL语句，获得一份试卷*/
SELECT t1.id, t1.paper_name FROM m_test_paper AS t1 ORDER BY rand() LIMIT 1;

SELECT
 t1.question_id,
 t1.question_order,
 t1.question_score,
 t2.question,
 t2.desc,
 t2.correct_option_id,
 t2.explain
FROM m_test_question AS t1
LEFT JOIN m_question AS t2 ON t1.question_id = t2.id
WHERE t1.test_paper_id = 1 AND t2.valid_flag = 1
ORDER BY t1.question_order;

/* 在页面上循环展示试卷及选项 */
SELECT
 t1.id, t1.option, t1.`order`,
 t1.question_id
FROM m_option AS t1
WHERE EXISTS (
 SELECT 1 FROM m_test_question AS t2
 WHERE t2.test_paper_id = 1 AND t2.question_id = t1.question_id
)
ORDER BY t1.question_id, t1.`order`;

/*用户作答提交之后，插入答卷结果及分数*/
/*一题一记录，循环时加得总分*/
INSERT INTO t_user_answer (user_id, question_id, user_option_id, test_date)
  VALUES ('#user_id', '#question_id', '#user_option_id', CURRENT_TIMESTAMP);

/*根据总分计算级别*/
/*'0-60'    '#score >= 0 && #score < 60'*/
/*'60-70'   '#score >= 60 && #score < 70'*/
/*'70-80'   '#score >= 70 && #score < 80'*/
/*'80-90'   '#score >= 80 && #score < 90'*/
/*'90-100'  '#score >= 90 && #score <= 100'*/
INSERT INTO t_score (user_id, test_paper_id, score, test_date) VALUES ('#user_id', '#test_paper_id', '#score', CURRENT_TIMESTAMP);

UPDATE m_user SET is_test = 1 WHERE id = '#id';

SELECT
 t.id AS dept_id,
 t.`name` AS dept_name,
 IFNULL(t3.user_num, 0) AS user_num
 FROM m_dept AS t
 LEFT JOIN (
  SELECT
   t2.dept_id,
   count(t1.user_id) AS user_num
  FROM t_score AS t1
  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
  GROUP BY t2.dept_id
 ) AS t3 ON t.id = t3.dept_id;

/*按部门查询考试用户信息*/
SELECT
 t1.user_id,
 t2.name,
 t2.identity_card,
 t2.org_name,
 t4.`name` AS dept_name,
 t3.paper_name,
 t1.score,
 t1.test_date
 FROM t_score AS t1
 LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
 LEFT JOIN m_test_paper t3 ON t1.test_paper_id = t3.id
 LEFT JOIN m_dept AS t4 ON t2.dept_id = t4.id
 WHERE t2.dept_id = '1';

/*按部门统计不同分数级别的用户数*/
SELECT t.`level`, count(user_id) AS user_num, concat(round(100 * count(user_id)/t.total_user, 2), '%') AS user_percent, round(avg(t.score),2) AS avg_score FROM (
SELECT
(
  CASE WHEN t1.score >= 0  AND t1.score < 60   THEN '0-60'
       WHEN t1.score >= 60 AND t1.score < 70   THEN '60-70'
       WHEN t1.score >= 70 AND t1.score < 80   THEN '70-80'
       WHEN t1.score >= 80 AND t1.score < 90   THEN '80-90'
       WHEN t1.score >= 90 AND t1.score <= 100 THEN '90-100'
  ELSE '其他' END
) AS level, t1.user_id, t1.score, (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '1') AS total_user
FROM t_score AS t1
LEFT JOIN m_user AS t2 ON t1.user_id = t2.id WHERE t2.dept_id = '1'
) AS t GROUP BY t.`level`;

SELECT
 t.`level`,
 t.user_num,
 concat(ifnull(convert(round(100 * t.user_num/t.total_user, 2), decimal), 0), '%') AS user_percent,
 t.avg_score FROM (
 SELECT
  '0-60' AS level,
  count(*) AS user_num,
  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '1') AS total_user,
  ifnull(round(avg(t1.score), 2), 0) AS avg_score
  FROM t_score AS t1
  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
  WHERE t2.dept_id = '1' AND t1.score >= 0  AND t1.score < 60
 UNION
 SELECT
  '60-70' AS level,
  count(*) AS user_num,
  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '1') AS total_user,
  ifnull(round(avg(t1.score), 2), 0) AS avg_score
  FROM t_score AS t1
  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
  WHERE t2.dept_id = '1' AND t1.score >= 60 AND t1.score < 70
 UNION
 SELECT
  '70-80' AS level,
  count(*) AS user_num,
  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '1') AS total_user,
  ifnull(round(avg(t1.score), 2), 0) AS avg_score
  FROM t_score AS t1
  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
  WHERE t2.dept_id = '1' AND t1.score >= 70 AND t1.score < 80
 UNION
 SELECT
  '80-90' AS level,
  count(*) AS user_num,
  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '1') AS total_user,
  ifnull(round(avg(t1.score), 2), 0) AS avg_score
  FROM t_score AS t1
  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
  WHERE t2.dept_id = '1' AND t1.score >= 80 AND t1.score < 90
 UNION
 SELECT
  '90-100' AS level,
  count(*) AS user_num,
  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '1') AS total_user,
  ifnull(round(avg(t1.score), 2), 0) AS avg_score
  FROM t_score AS t1
  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
  WHERE t2.dept_id = '1' AND t1.score >= 90 AND t1.score <= 100
 ) AS t;
