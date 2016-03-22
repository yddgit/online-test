/*查看用户是否已答过题*/
SELECT count(*) FROM m_user AS t1 WHERE t1.identity_card = '#identity_card' AND t1.is_test = 1;

/*如果用户已答过题，显示分数*/
SELECT t1.id, t2.name, t4.paper_name, t1.score, t1.test_date, t2.org_name, t3.name AS dept_name FROM t_score AS t1
LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
LEFT JOIN m_dept AS t3 ON t2.dept_id = t3.id
LEFT JOIN m_test_paper AS t4 ON t1.test_paper_id = t4.id
WHERE t2.identity_card = '#identity_card'

/*如果用户未答过题，则检查用户是否录入过信息*/

/*如未录入，则先录入信息*/
SELECT count(*) FROM m_user AS t1 WHERE t1.identity_card = '#identity_card';
INSERT INTO m_user (name, identity_card, org_name, dept_id, is_test) VALUES ('#name', '#identity_card', '#org_name', 'dept_id', 0);

/*抽取试卷进行答题*/
/*循环执行以下SQL语句100次，获得一份试卷*/
SELECT t1.id, t1.paper_name FROM m_test_paper AS t1 ORDER BY rand() LIMIT 1;

SELECT t1.question_id, t1.test_paper_id, t1.question_order, t1.question_score, t2.question, t2.desc, t2.correct_option_id, t2.explain
FROM m_test_question AS t1 LEFT JOIN m_question AS t2 on t1.question_id = t2.id
WHERE t1.test_paper_id = 1 AND t2.valid_flag = 1 ORDER BY t1.question_order;

/* 在页面上循环展示试卷及选项 */
SELECT t1.id, t1.option, t1.question_id FROM m_option AS t1 WHERE t1.question_id = '#question_id' ORDER BY t1.order;

/*用户作答提交之后，插入答卷结果及分数*/
/*一题一记录，循环时加得总分*/
INSERT INTO t_user_answer (user_id, question_id, user_option_id, correct_option_id, test_date)
  VALUES ('#user_id', '#question_id', '#user_option_id', '#correct_option_id', CURRENT_TIMESTAMP);

/*根据总分计算级别*/
/*'0-60'    '#score >= 0 && #score < 60'*/
/*'60-70'   '#score >= 60 && #score < 70'*/
/*'70-80'   '#score >= 70 && #score < 80'*/
/*'80-90'   '#score >= 80 && #score < 90'*/
/*'90-100'  '#score >= 90 && #score <= 100'*/
INSERT INTO t_score (user_id, test_paper_id, score, level, test_date) VALUES ('#user_id', '#test_paper_id', '#score', '#level', CURRENT_TIMESTAMP);

/*按部门查询测试用户信息*/
SELECT t1.user_id, t2.name, t2.identity_card, t2.org_name, t3.paper_name, t1.score, t1.test_date FROM t_score AS t1
LEFT JOIN m_user AS t2 ON t1.user_id = t2.id
LEFT JOIN m_test_paper t3 ON t1.test_paper_id = t3.id
WHERE t2.dept_id = '#dept_id';

/*按部门统计不同分数级别的用户数*/
SELECT t1.level, count(*) AS user_num FROM t_score AS t1
LEFT JOIN m_user AS t2 ON t1.user_id = t2.id WHERE t2.dept_id = '#dept_id' GROUP BY t1.level;
