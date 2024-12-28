DROP TABLE IF EXISTS MealFoods;
DROP TABLE IF EXISTS Meal;
DROP TABLE IF EXISTS FoodLog;
DROP TABLE IF EXISTS CustomFood ;
DROP TABLE IF EXISTS Food;
DROP TABLE IF EXISTS User;
CREATE TABLE User(
    username    VARCHAR(20),
    goal        INTEGER, 
    
    PRIMARY KEY (username)
);


CREATE TABLE Food(
    f_name          VARCHAR(20),
    cal_per_oz      INTEGER,
    oz_per_serving  INTEGER,

    PRIMARY KEY (f_name)
);

CREATE TABLE CustomFood(
    username        VARCHAR(20),
    f_name          VARCHAR(20),
    cal_per_oz      INTEGER,
    oz_per_serving  INTEGER,

    FOREIGN KEY (username) REFERENCES User(username),
    PRIMARY KEY (f_name, username)

);

CREATE TABLE FoodLog(
    user        VARCHAR(20),
    f_name      VARCHAR(20),
    calories    INTEGER,
    log_date    DATETIME,

    FOREIGN KEY (user) REFERENCES User(username),
    FOREIGN KEY (f_name) REFERENCES Food(f_name),

    PRIMARY KEY (user, f_name, log_date)
);

CREATE TABLE Meal(
    m_name      VARCHAR(20),
    username    VARCHAR(20),
    calories    INTEGER,

    FOREIGN KEY (username) REFERENCES User(username),
    PRIMARY KEY (m_name, username)
);

CREATE TABLE MealFoods(
    m_name  VARCHAR(20),
    f_name  VARCHAR(20),
    user    VARCHAR(20),
    f_oz    INT,

    FOREIGN KEY (m_name) REFERENCES Meal(m_name),
    FOREIGN KEY (f_name) REFERENCES Food(f_name),
    FOREIGN KEY (user) REFERENCES User(username),
    PRIMARY KEY (m_name, f_name, user)
);


CREATE TABLE MealLog(
    m_name      VARCHAR(20),
    user        VARCHAR(20),
    log_date    DATETIME,

    FOREIGN KEY (m_name) REFERENCES Meal(m_name),
    FOREIGN KEY (user) REFERENCES User(username),
    PRIMARY KEY (m_name, user, log_date)
);

CREATE TABLE CustomLog(
    f_name      VARCHAR(20),
    user        VARCHAR(20),
    calories    INT,
    log_date    DATETIME,

    FOREIGN KEY (f_name) REFERENCES CustomFood(f_name),
    FOREIGN KEY (user) REFERENCES User(username),
    PRIMARY KEY (f_name, user, log_date)

);


