CREATE DATABASE period_calculators;
USE period_calculators;
-- DROP DATABASE period_calculators;

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL
);

CREATE TABLE `author` (
  `author_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `author_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL
);


CREATE TABLE `blog` (
  `blog_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `blog_title` varchar(255) NOT NULL,
  `blog_body` longtext NOT NULL,
  `blog_image` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `publish_date` timestamp NOT NULL DEFAULT current_timestamp()
);

INSERT INTO `categories` (`cat_id`, `cat_name`) VALUES
(1, 'Menstrual cycle'),
(2, 'Menstrual in Nepal');

INSERT INTO `author` (`author_id`, `author_name`, `email`, `password`, `role`) VALUES
(1, 'Dr.Sita Upadhya', 'sita@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1),
(2, 'Subash Pandey', 'pandeysubash404@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1),
(3, 'Dr.Pratikshya Khadka', 'pratikshya@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 0),
(4, 'Dr.Sonali Poudel', 'sonali@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 0);

INSERT INTO `blog` (`blog_id`, `blog_title`, `blog_body`, `blog_image`, `category`, `author_id`, `publish_date`) VALUES
(1, 'How Many Days Typically Pass Between Menstrual Periods?', '<h3>Is it consistent?</h3><p>The average menstrual cycle is about 28 days. This means that about 28 days pass between the first day of your period and the first day of your next period.</p> <p>Not everyone has this textbook cycle, though. You may find that your periods typically occur every <span>21 to 35 days.</span></p><p>Periods that are closer together or further apart aren’t always cause for concern.</p><p>Tracking your menstrual patterns may help you better understand your overall cycle as well as reveal symptoms you should discuss with a doctor or other healthcare provider.</p> <blockquote><p>Menstrual flow lengths vary and may last anywhere between two and seven days. Flow is generally heavier in the first days and may trail off to light or spotting in the final days.</p></blockquote><p></p><br><h3>What if my periods are more frequent than every 21 days?</h3><p>There are many situations where your period may come more frequently than every 21 days.</p><p>People in perimenopause, for example, may experience shorter, more irregular cycles until they reach menopause.</p><p>Other factors that may shorten cycle length include:</p><ul><li>Stress</li><li>Temporary illness, such as the flu</li><li>Significant weight changes</li><li>Hormonal birth control</li><li>Uterine fibroids</li><li>Lack of ovulation (anovulatiion)</li></ul><p>Oftentimes, your cycle will resolve on its own.</p><p>If you’re still experiencing shorter cycles (having more than one period in a single month), see a doctor after six weeks of irregularity.</p>
<h3>What if my periods are further apart than every 35 days?</h3><p>Menstruating individuals usually begin having a period between ages 9 and 15. The average person experiences at least four periods during their first year of menstruation.</p><p>This number will gradually increase with time, with the average adult having at least nine periods a year. This means that some periods may naturally occur more than 35 days apart.</p><p>Occasional lateness may also be caused by:</p><ul><li>Stress</li><li>Intense exercise</li><li>Significant weight changes</li><li>Hormonal birth control</li><li>Perimenopause</li></ul><p>Chronic lateness may be caused by an underlying condition. Polycystic ovary syndrome (PCOS), for example, can cause:</p><ul><li>Irregular periods</li><li>Excess hair growth on the body</li><li>Unexpected weight gain</li></ul><p>Premature ovarian failure may also cause irregular or occasional periods in menstruating individuals under age 40.</p><p>Pregnancy is another possibility. If you’re sexually active, it may be a good idea to take a home pregnancy test.</p><p>If you suspect pregnancy or another underlying condition is to blame, make an appointment with a doctor. They can assess your symptoms and advise you on any next steps.</p>','blog-1.jpg',2,1,'2024-03-28 01:12:00');

INSERT INTO `blog` (`blog_id`, `blog_title`, `blog_body`, `blog_image`, `category`, `author_id`, `publish_date`) VALUES 
(2,'All about Menstrual Periods','<h3>What Does a Menstrual Period Feel Like?</h3><p>A few days before and during your period, you might feel cramping and bloating in your abdomen. The cramps are caused by increased production of hormones. These hormones (called prostaglandins) cause the muscles of the uterus to contract.</p> <p>Many teens who have cramps also notice aching in the upper thighs along with lower back pain. Some also notice nausea, diarrhea, irritability, headaches, and fatigue, among other symptoms.</p><p>To ease cramping, try applying heat to your abdomen with a heating pad or hot water bottle. Taking a warm bath may also help. Some teens find that exercise helps relieve cramps. Exercise improves blood flow and produces endorphins, the body’s natural painkillers.</p><p>Simple but effective non-prescription pain relieving medications can ease symptoms. These include acetaminophen (Tylenol) and non-steroidal anti-inflammatory drugs (NSAIDs). NSAIDs include medications like ibuprofen (such as Motrin and Advil) and naproxen (such as Aleve). These drugs block the effects of prostaglandin hormones.</p><p>Discuss symptoms with your primary health care practitioner, so you can find the best medications and dosage.</p><p>Talk to your primary health care provider or your gynecologist if:</p>
<ul><li>Your cramps are severe</li><li>Your bleeding is excessive, lasts longer than 7 days, occurs often or at the wrong time of your cycle</li><li>If you have not had your first period by age 16</li><li>If it has been 3 months since your last period</li><li>You think you might be pregnant</li><li>You develop fever and feel sick after tampon use</li></ul><p>Cramps are normally worst during the first two to three days of your period, then ease as prostaglandin levels in the body return to normal. If cramps stay about the same throughout your period, or if over-the-counter painkillers don`t really work, see a doctor.</p><p>Always ask your primary health care provider any questions you have about your period, making sure you clearly and completely describe any concerns.</p>','blog-2.jpg', 1, 3,'2024-03-01 03:57:00');

INSERT INTO `blog` (`blog_id`, `blog_title`, `blog_body`, `blog_image`, `category`, `author_id`, `publish_date`) VALUES
(3,'Let’s talk menstruation in Nepal!',
'<h3>What Does a Menstrual Period Feel Like?</h3><p>Here’s why. Nepal is home to around 15.9 million adolescent girls— that’s roughly 54.19% of the population. A girl menstruates on an average for five days a month, 12 months a year, and the cycle carries on till she reaches menopause in 30–40 years. Periods are normal and healthy, yet many girls across rural and urban Nepal struggle to manage this monthly occurrence.</p><p>The statistics are stark and dismal: 88% of girls and women who menstruate use unsafe materials; 66% of girls are unware of menstruation before their first period; 70% mothers think periods are dirty; 66% girls and women manage periods without toilets. </p>
<p>Handling a normal physiological event is hugely complex, influenced by socio-cultural norms and the larger political environment that shape how girls experience their periods, what they can do while menstruating, what they can use to absorb menstrual blood and how they dispose the material, whether and from whom they can seek information and help, and even whether they stay in school or not.</p><blockquote><p>Teachers, health care providers, and others who come in contact with girls through schools, health centres also shape her experiences.</p></blockquote>
<p> When a girl faces obstacles in managing her menses in a healthy way, she is at risk for infection, her self-esteem and self-confidence suffer, she may remain absent from school during her period, or worse still, drop out of school altogether upon reaching puberty. Over time, these negative effects add up, preventing a young girl from achieving her full potential and having a healthy, productive life. So what are we, as professionals, doing to help girls have healthy and safe periods?</p>
<p>Let us break down what it takes for a girl to manage her periods in a healthy way that respects and upholds her dignity, and privacy. We need “hardware”, “software”, and a conducive environment that supports these elements while empowering girls. Software includes information about, favourable social norms and healthy attitudes towards menstruation.</p>
<p>A girl need to know why she has periods and what is happening to her body when she menstruates. She needs to be aware about the range of safe and hygienic menstrual absorbents available — both reusable and disposable. Negative and harmful menstruation related social norms and taboos can perpetrate a culture of silence and negative attitudes, and should be addressed to ease long term repercussions.</p>
<p>Menstruation matters to our girls, and it should matter to everyone, everywhere. We experience it and we shape its experience. As influencers, development professionals, and policy makers, we must take action now. Period.</p>', 'blog-3.jpg', 1, 4, '2024-03-02 02:18:00');

INSERT INTO `blog` (`blog_id`, `blog_title`, `blog_body`, `blog_image`, `category`, `author_id`, `publish_date`) VALUES
('4', 'Stages of the Menstrual Cycle', 
'<p>The purpose of the monthly menstrual cycle is to prepare for pregnancy. Menstrual cycles vary in length and intensity.</p><p>During each menstrual cycle, an egg develops and is released from the <span style="color:#FB5988;">ovaries</span>. The lining of the <span style="color:#FB5988;">uterus</span> builds up. If a pregnancy doesn’t happen, the uterine lining sheds during a menstrual period. Then the cycle starts again.</p><p>The menstrual cycle is divided into four phases:</p><ul><li>menstrual phase</li><li>follicular phase</li><li>ovulation phase</li><li>luteal phase</li></ul><p>The length of each phase can vary and change over time.</p><h4>Menstrual phase</h4><p>The menstrual phase is the first stage of the menstrual cycle. It’s also when you get your period.</p><p>This phase starts when an egg from the previous cycle isn’t fertilized. Because pregnancy hasn’t taken place, levels of the hormones estrogen and progesterone drop.</p><p>The thickened lining of your uterus, which would support a pregnancy, is no longer needed, so it sheds through your vagina. During your period, you release a combination of blood, mucus, and tissue from your uterus.</p><p>You may have period symptoms like these:</p><ul><li>cramps (try <span style="color:#FB5988;">home remedies</span>)</li><li>tender breasts</li><li>bloating</li><li>mood swings</li><li>irritability</li><li>headaches</li><li>tiredness</li><li>low back pain</li></ul><h4>Follicular phase</h4><p>The follicular phase starts on the first day of your period (so there is some overlap with the menstrual phase) and ends when you ovulate.</p><p>It starts when the hypothalamus signals your pituitary gland to release <span style="color:#FB5988;">follicle-stimulating hormone (FSH)</span>. This hormone stimulates your ovaries to produce around 5 to 20 small sacs called follicles. Each follicle contains an immature egg.</p><p>Only the healthiest egg will eventually mature. (On rare occasions, a female may have two eggs mature.) The rest of the follicles will be reabsorbed into your body.</p><p>The maturing follicle sets off a surge in estrogen that thickens the lining of your uterus. This creates a nutrient-rich environment for an embryo to grow.</p><p>The <span style="color:#FB5988;">average follicular phase</span> lasts for about 16 days. It can range from 11 to 27 days, depending on your cycle.</p><h4>Ovulation phase</h4>
<p>Rising estrogen levels during the follicular phase trigger your pituitary gland to release <span style="color:#FB5988;">luteinizing hormone (LH)</span>. This is what starts the process of ovulation.</p><p>Ovulation is when your ovary releases a mature egg. The egg travels down the fallopian tube toward the uterus to be fertilized by sperm.</p><p>The ovulation phase is the time during your menstrual cycle when you can get pregnant. You can tell that you’re ovulating by symptoms like these:</p><ul><li>a slight rise in <span style="color:#FB5988;">basal body temperature</span></li><li>thicker discharge that has the texture of egg whites</li></ul><p>Ovulation happens around day 14 if you have a 28-day cycle — right in the middle of your menstrual cycle. It lasts about 24 hours. After a day, the egg will die or dissolve if it isn’t fertilized.</p><blockquote><p><strong>DID YOU KNOW?</strong></p><p>Because sperm can live up to 5 days, pregnancy can occur as a result of sex 5 days before ovulation.</p></blockquote><h4>Luteal phase</h4><p>After the follicle releases its egg, it changes into the <span style="color:#FB5988;">corpus luteum</span>. This structure releases hormones, mainly progesterone and some estrogen. The rise in hormones keeps your uterine lining thick and ready for a fertilized egg to implant.</p><p>If you do get pregnant, your body will produce human Chorionic Gonadotropin (hCG). This is the hormone <span style="color:#FB5988;">pregnancy tests detect</span>. It helps maintain the corpus luteum and keeps the uterine lining thick.</p><p>If you don’t get pregnant, the corpus luteum will shrink away and be resorbed. This leads to decreased levels of estrogen and progesterone, which causes the onset of your period. The uterine lining will shed during your period.</p><p>During this phase, if you don’t get pregnant, you may experience symptoms of <span style="color:#FB5988;">premenstrual syndrome (PMS)</span>. These include:</p><ul><li>bloating</li><li>breast swelling, pain, or tenderness</li><li>mood changes</li><li>headache</li><li>weight gain</li><li>changes in sexual desire</li><li>food cravings</li><li>trouble sleeping</li></ul><p>The luteal phase lasts for 11 to 17 days. The <span style="color:#FB5988;">average length</span> is 14 days.</p><h4>Identifying common issues</h4><p>Every menstrual cycle is different. Some people get their period at the same time each month.
 Others are more <span style="color:#FB5988;">irregular</span>. Some bleed more <span style="color:#FB5988;">heavily</span> or for a longer number of days than others.</p><p>Your menstrual cycle can also change during certain times of your life. For example, it can get irregular as you get close to <span style="color:#FB5988;">menopause</span>.</p><p>One way to find out if you’re having any issues with your menstrual cycle is to track your periods. Write down when they start and end. Also record any changes to the amount or number of days you bleed, and whether you have <span style="color:#FB5988;">spotting between periods</span>.</p><p>Any of these things can alter your menstrual cycle:</p><ul><li><strong>Birth control.</strong> The birth control pill may make your periods shorter and lighter. While on some pills, you won’t get a period at all.</li><li><strong>Pregnancy. </strong>Your periods should stop during pregnancy. Missed periods are one of the most obvious <span style="color:#FB5988;">first signs</span> that you’re pregnant.</li><li><strong>Polycystic ovary syndrome (PCOS).</strong> This hormonal imbalance prevents an egg from developing normally in the ovaries. PCOS causes irregular menstrual cycles and missed periods.</li><li><strong>Uterine fibroids.</strong> These noncancerous growths in your uterus can make your periods longer and heavier than usual.</li><li><strong>Eating disorders. </strong>Anorexia, bulimia, and other eating disorders can disrupt your menstrual cycle and make your periods stop.</li></ul><p>Here are a few signs of a problem with your menstrual cycle:</p><ul><li>You’ve skipped periods, or your periods have stopped entirely.</li><li>Your periods are irregular.</li><li>You bleed for more than 7 days.</li><li>Your periods are less than 21 days or more than 35 days apart.</li><li>You bleed between periods (heavier than spotting).</li></ul><p>If you have these or other problems with your menstrual cycle or periods, talk with a healthcare professional.</p><h4><strong>The takeaway</strong></h4><p>Every menstrual cycle is different. What’s typical for you might not be for someone else.</p><p>It’s important to get familiar with your cycle — including when you get your periods and how long they last. Be alert for any changes and report them to a healthcare professional.</p>',
 'menstural_cycle.png', '1', '1', '2024-04-29 10:34:54');


Create TABLE `users`(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name varchar(50) NOT NULL,
 email varchar(50) NOT NULL,
 password varchar(50) NOT NULL,
 dob date NOT NULL,
 weight float(5) NOT NULL
);

INSERT INTO `users` VALUES(1,"Subash","pandeysubash404@gmail.com","d1e6989494884caca3c994c88b050cd8",'2002-05-02',39.8);

 SELECT * FROM `users`;


CREATE TABLE symptom (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    flow ENUM('none','light', 'medium', 'heavy', 'disaster'),
    symptoms TEXT,
    temperature DECIMAL(5,2),
    date DATE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO `symptom` (id, title, description, flow, symptoms, temperature, date, user_id)
VALUES (1, 'Bleeding','This is description','light','Headache, Low Back Pain',94.8,'2024-04-21',1);

SELECT * FROM `symptom`;

--  DROP TABLE symptom;

-- Create the cycles table
CREATE TABLE `cycles` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    start_date DATE NOT NULL,
    period_length INT NOT NULL,
    cycle_length INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert sample data
INSERT INTO `cycles` (start_date, period_length, cycle_length, user_id)
VALUES (1,'2024-02-15', 5, 37, 1),
(2,'2024-03-23', 6, 35,1),
(3,'2024-04-27', 4, 0,1);

select * from cycles;
-- WHERE user_id=1;

--  DROP TABLE cycles;

ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;