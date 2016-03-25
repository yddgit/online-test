SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for m_dept
-- ----------------------------
DROP TABLE IF EXISTS `m_dept`;
CREATE TABLE `m_dept` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '部门ID',
  `name` varchar(512) NOT NULL DEFAULT '部门名称未指定' COMMENT '部门名称',
  `valid_flag` int(11) NOT NULL DEFAULT '1' COMMENT '有效标识：0无效，1有效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='部门表';

-- ----------------------------
-- Records of m_dept
-- ----------------------------
INSERT INTO `m_dept` VALUES ('1', '行政部', '1');
INSERT INTO `m_dept` VALUES ('2', '人事部', '1');
INSERT INTO `m_dept` VALUES ('3', '账务部', '1');
INSERT INTO `m_dept` VALUES ('4', '采购部', '1');
INSERT INTO `m_dept` VALUES ('5', '开发部', '1');
INSERT INTO `m_dept` VALUES ('6', '测试部', '1');
INSERT INTO `m_dept` VALUES ('7', '运营支持部', '1');
INSERT INTO `m_dept` VALUES ('8', '市场公关部', '1');
INSERT INTO `m_dept` VALUES ('9', '客服部', '1');

-- ----------------------------
-- Table structure for m_option
-- ----------------------------
DROP TABLE IF EXISTS `m_option`;
CREATE TABLE `m_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `option` text NOT NULL COMMENT '选项内容',
  `order` int(11) NOT NULL DEFAULT '1' COMMENT '选项顺序',
  `question_id` int(11) NOT NULL COMMENT '选项所属问题ID',
  PRIMARY KEY (`id`),
  KEY `FK_OPTION_QUESTION_ID` (`question_id`),
  CONSTRAINT `FK_OPTION_QUESTION_ID` FOREIGN KEY (`question_id`) REFERENCES `m_question` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='选项表';

-- ----------------------------
-- Records of m_option
-- ----------------------------
INSERT INTO `m_option` VALUES ('1', 'public int method 1 (int a, int b) { return 0; }', '1', '1');
INSERT INTO `m_option` VALUES ('2', 'private int method1 (int a, int b) { return 0; }', '2', '1');
INSERT INTO `m_option` VALUES ('3', 'private int method1 (int a, long b) { return 0; }', '3', '1');
INSERT INTO `m_option` VALUES ('4', 'public short method1 (int a, int b) { return 0; }', '4', '1');
INSERT INTO `m_option` VALUES ('5', 'True', '1', '2');
INSERT INTO `m_option` VALUES ('6', 'False', '2', '2');
INSERT INTO `m_option` VALUES ('7', 'Child.test()\nBase.test()', '1', '3');
INSERT INTO `m_option` VALUES ('8', 'Base.test()\nChild.test()', '2', '3');
INSERT INTO `m_option` VALUES ('9', 'Base.test()', '3', '3');
INSERT INTO `m_option` VALUES ('10', 'Child.test()', '4', '3');
INSERT INTO `m_option` VALUES ('11', '实例变量是类的成员变量', '1', '4');
INSERT INTO `m_option` VALUES ('12', '实例变量用关键字static声明', '2', '4');
INSERT INTO `m_option` VALUES ('13', '在方法中定义的局部变量在该方法被执行时创建', '3', '4');
INSERT INTO `m_option` VALUES ('14', '局部变量在使用前必须被初始化', '4', '4');
INSERT INTO `m_option` VALUES ('15', 'abstract不能与final并列修饰同一个类', '1', '5');
INSERT INTO `m_option` VALUES ('16', 'abstract类中可以有private的成员', '2', '5');
INSERT INTO `m_option` VALUES ('17', 'abstract方法必须在abstract类中', '3', '5');
INSERT INTO `m_option` VALUES ('18', 'static方法中能处理非static的属性', '4', '5');
INSERT INTO `m_option` VALUES ('19', 'x[24]为0', '1', '6');
INSERT INTO `m_option` VALUES ('20', 'x[24]未定义', '2', '6');
INSERT INTO `m_option` VALUES ('21', 'x[25]为0', '3', '6');
INSERT INTO `m_option` VALUES ('22', 'x[0]为空', '4', '6');
INSERT INTO `m_option` VALUES ('23', 'class中的constructor不可省略', '1', '7');
INSERT INTO `m_option` VALUES ('24', 'constructor必须与class同名，但方法不能与class同名', '2', '7');
INSERT INTO `m_option` VALUES ('25', 'constructor在一个对象被new时执行', '3', '7');
INSERT INTO `m_option` VALUES ('26', '一个class只能定义一个constructor', '4', '7');
INSERT INTO `m_option` VALUES ('27', '实例方法可直接调用超类的实例方法', '1', '8');
INSERT INTO `m_option` VALUES ('28', '实例方法可直接调用超类的类方法', '2', '8');
INSERT INTO `m_option` VALUES ('29', '实例方法可直接调用其他类的实例方法', '3', '8');
INSERT INTO `m_option` VALUES ('30', '实例方法可直接调用本类的类方法', '4', '8');
INSERT INTO `m_option` VALUES ('31', 'abstract修饰符可修饰字段、方法和类', '1', '9');
INSERT INTO `m_option` VALUES ('32', '抽象方法的body部分必须用一对大括号{ }包住', '2', '9');
INSERT INTO `m_option` VALUES ('33', '声明抽象方法，大括号可有可无', '3', '9');
INSERT INTO `m_option` VALUES ('34', '声明抽象方法不可写出大括号', '4', '9');
INSERT INTO `m_option` VALUES ('35', '不必事先估计存储空间', '1', '10');
INSERT INTO `m_option` VALUES ('36', '可随机访问任一元素', '2', '10');
INSERT INTO `m_option` VALUES ('37', '插入删除不需要移动元素', '3', '10');
INSERT INTO `m_option` VALUES ('38', '所需空间与线性表长度成正比', '4', '10');
INSERT INTO `m_option` VALUES ('39', 'Int16', '1', '11');
INSERT INTO `m_option` VALUES ('40', 'Int32', '2', '11');
INSERT INTO `m_option` VALUES ('41', 'int', '3', '11');
INSERT INTO `m_option` VALUES ('42', 'long', '4', '11');
INSERT INTO `m_option` VALUES ('43', '在现实生活中，对象是指客观世界的实体', '1', '12');
INSERT INTO `m_option` VALUES ('44', '程序中的对象就是现实生活中的对象', '2', '12');
INSERT INTO `m_option` VALUES ('45', '在程序中，对象是通过一种抽象数据类型来描述的，这种抽象数据类型称为类（class）', '3', '12');
INSERT INTO `m_option` VALUES ('46', '在程序中，对象是一组变量和相关方法的集合', '4', '12');

-- ----------------------------
-- Table structure for m_question
-- ----------------------------
DROP TABLE IF EXISTS `m_question`;
CREATE TABLE `m_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '问题ID',
  `question` text NOT NULL COMMENT '问题内容',
  `desc` text COMMENT '问题描述',
  `correct_option_id` varchar(255) NOT NULL DEFAULT '' COMMENT '问题答案（如1,2表示正确选项ID是1和2）',
  `explain` text COMMENT '答案解释',
  `valid_flag` int(11) NOT NULL DEFAULT '1' COMMENT '有效标识：0无效，1有效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='问题表';

-- ----------------------------
-- Records of m_question
-- ----------------------------
INSERT INTO `m_question` VALUES ('1', '下面中哪两个可以在A的子类中使用：', 'class A {\n  protected int method1 (int a, int b) {\n    return 0;\n  }\n}', '1,3', '', '1');
INSERT INTO `m_question` VALUES ('2', 'Abstract method cannot be static. True or False ?', '', '5', '', '1');
INSERT INTO `m_question` VALUES ('3', 'What will be the output when you compile and execute the following program.', 'class Base\n{\n void test() {\n  System.out.println(\"Base.test()\");\n }\n}\n\nclass Child extends Base {\n void test() {\n  System.out.println(\"Child.test()\");\n }\n static public void main(String[] a) {\n  Child anObj = new Child();\n  Base baseObj = (Base) anObj;\n  baseObj.test();\n }\n}', '10', '', '1');
INSERT INTO `m_question` VALUES ('4', '下面关于变量及其范围的陈述哪些是不正确的：', '', '12,13', '由static修饰的变量称为类变量或是静态变量', '1');
INSERT INTO `m_question` VALUES ('5', '下列关于修饰符混用的说法，错误的是：', '', '18', '静态方法中不能引用非静态的成员', '1');
INSERT INTO `m_question` VALUES ('6', '执行完以下代码int [ ] x = new int[25]；后，以下哪项说明是正确的：', '', '19', 'x属于引用类型，该引用类型的每一个成员是int类型，默认值为：0', '1');
INSERT INTO `m_question` VALUES ('7', '下列说法正确的有', '', '25', '构造方法的作用是在实例化对象的时候给数据成员进行初始化', '1');
INSERT INTO `m_question` VALUES ('8', '下列哪种说法是正确的', '', '30', '', '1');
INSERT INTO `m_question` VALUES ('9', '下列哪一种叙述是正确的', '', '34', 'abstract可以修饰方法和类，不能修饰属性。抽象方法没有方法体，即没有大括号{}', '1');
INSERT INTO `m_question` VALUES ('10', '链表具有的特点是：', '', '35,37,38', '', '1');
INSERT INTO `m_question` VALUES ('11', 'Java语言中，String类的IndexOf()方法返回的类型是？', '', '41', 'indexOf方法的声明为：public int indexOf(int ch)\n在此对象表示的字符序列中第一次出现该字符的索引；如果未出现该字符，则返回 -1。', '1');
INSERT INTO `m_question` VALUES ('12', '以下关于面向对象概念的描述中，不正确的一项是：', '', '44', '', '0');

-- ----------------------------
-- Table structure for m_test_paper
-- ----------------------------
DROP TABLE IF EXISTS `m_test_paper`;
CREATE TABLE `m_test_paper` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '试卷ID',
  `paper_name` varchar(255) NOT NULL DEFAULT '试卷名未指定' COMMENT '试卷名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='试卷表';

-- ----------------------------
-- Records of m_test_paper
-- ----------------------------
INSERT INTO `m_test_paper` VALUES ('1', 'A卷');
INSERT INTO `m_test_paper` VALUES ('2', 'B卷');

-- ----------------------------
-- Table structure for m_test_question
-- ----------------------------
DROP TABLE IF EXISTS `m_test_question`;
CREATE TABLE `m_test_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '试卷题目ID',
  `test_paper_id` int(11) NOT NULL COMMENT '试卷ID',
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `question_order` int(11) NOT NULL DEFAULT '1' COMMENT '问题顺序',
  `question_score` int(11) NOT NULL DEFAULT '2' COMMENT '问题得分',
  PRIMARY KEY (`id`),
  KEY `FK_TEST_QUESTION_PAPER_ID` (`test_paper_id`),
  KEY `FK_TEST_QUESTION_QUESTION_ID` (`question_id`),
  CONSTRAINT `FK_TEST_QUESTION_QUESTION_ID` FOREIGN KEY (`question_id`) REFERENCES `m_question` (`id`),
  CONSTRAINT `FK_TEST_QUESTION_PAPER_ID` FOREIGN KEY (`test_paper_id`) REFERENCES `m_test_paper` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='试卷题目表';

-- ----------------------------
-- Records of m_test_question
-- ----------------------------
INSERT INTO `m_test_question` VALUES ('1', '1', '1', '1', '2');
INSERT INTO `m_test_question` VALUES ('2', '1', '2', '2', '2');
INSERT INTO `m_test_question` VALUES ('3', '1', '3', '3', '2');
INSERT INTO `m_test_question` VALUES ('4', '1', '4', '4', '2');
INSERT INTO `m_test_question` VALUES ('5', '1', '5', '5', '2');
INSERT INTO `m_test_question` VALUES ('6', '1', '6', '6', '2');
INSERT INTO `m_test_question` VALUES ('7', '2', '7', '1', '2');
INSERT INTO `m_test_question` VALUES ('8', '2', '8', '2', '2');
INSERT INTO `m_test_question` VALUES ('9', '2', '9', '3', '2');
INSERT INTO `m_test_question` VALUES ('10', '2', '10', '4', '2');
INSERT INTO `m_test_question` VALUES ('11', '2', '11', '5', '2');
INSERT INTO `m_test_question` VALUES ('12', '2', '12', '6', '2');

-- ----------------------------
-- Table structure for m_user
-- ----------------------------
DROP TABLE IF EXISTS `m_user`;
CREATE TABLE `m_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `name` varchar(255) NOT NULL COMMENT '用户姓名',
  `identity_card` varchar(255) NOT NULL DEFAULT '身份证号未指定' COMMENT '身份证号',
  `org_name` varchar(512) NOT NULL DEFAULT '单位名称未指定' COMMENT '单位名称',
  `dept_id` int(11) NOT NULL COMMENT '用户所属的部门ID',
  `is_test` int(11) NOT NULL DEFAULT '0' COMMENT '是否已经考过试：0未考试，1已考试',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_IDENTITY_CARD` (`identity_card`),
  KEY `FK_USER_DEPT_ID` (`dept_id`),
  CONSTRAINT `FK_USER_DEPT_ID` FOREIGN KEY (`dept_id`) REFERENCES `m_dept` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of m_user
-- ----------------------------

-- ----------------------------
-- Table structure for t_score
-- ----------------------------
DROP TABLE IF EXISTS `t_score`;
CREATE TABLE `t_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '得分记录ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `test_paper_id` int(11) NOT NULL COMMENT '试卷ID',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '用户得分',
  `test_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '考试时间',
  PRIMARY KEY (`id`),
  KEY `FK_SCORE_USER_ID` (`user_id`),
  KEY `FK_SCORE_TEST_PAPER_ID` (`test_paper_id`),
  CONSTRAINT `FK_SCORE_TEST_PAPER_ID` FOREIGN KEY (`test_paper_id`) REFERENCES `m_test_paper` (`id`),
  CONSTRAINT `FK_SCORE_USER_ID` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='得分记录表';

-- ----------------------------
-- Records of t_score
-- ----------------------------

-- ----------------------------
-- Table structure for t_user_answer
-- ----------------------------
DROP TABLE IF EXISTS `t_user_answer`;
CREATE TABLE `t_user_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '得分ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `question_id` int(11) NOT NULL COMMENT '问题',
  `user_option_id` varchar(255) NOT NULL COMMENT '用户选项',
  `test_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '考试时间',
  PRIMARY KEY (`id`),
  KEY `FK_ANSWER_USER_ID` (`user_id`),
  KEY `FK_ANSWER_QUESTION_ID` (`question_id`),
  CONSTRAINT `FK_ANSWER_QUESTION_ID` FOREIGN KEY (`question_id`) REFERENCES `m_question` (`id`),
  CONSTRAINT `FK_ANSWER_USER_ID` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户答案表';

-- ----------------------------
-- Records of t_user_answer
-- ----------------------------
