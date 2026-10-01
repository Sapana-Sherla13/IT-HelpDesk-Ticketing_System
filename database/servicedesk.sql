-- ============================================
-- IT HELPDESK DATABASE
-- PART 1
-- ============================================

CREATE DATABASE IF NOT EXISTS it_helpdesk;
USE it_helpdesk;

-- ============================================
-- ROLES TABLE
-- ============================================

DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    RoleName VARCHAR(50) NOT NULL
);

INSERT INTO roles (ID, RoleName) VALUES
(1,'Admin'),
(2,'Employee');

-- ============================================
-- USERS TABLE
-- ============================================

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    Email VARCHAR(150) NOT NULL UNIQUE,
    Phone VARCHAR(15),
    Password VARCHAR(255) NOT NULL,
    RoleId INT NOT NULL,
    IsActive TINYINT(1) DEFAULT 1,
    CreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_users_roles
    FOREIGN KEY (RoleId)
    REFERENCES roles(ID)
);

INSERT INTO users
(
ID,
FirstName,
LastName,
Email,
Phone,
Password,
RoleId,
IsActive
)
VALUES
(
1,
'Admin',
'User',
'admin@gmail.com',
'9876543210',
'admin123',
1,
1
);

-- ============================================
-- CATEGORIES TABLE
-- ============================================

DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryName VARCHAR(100) NOT NULL UNIQUE,
    IsActive TINYINT(1) DEFAULT 1
);

INSERT INTO categories (ID, CategoryName, IsActive) VALUES
(1,'Hardware',1),
(2,'Software',1),
(3,'Network',1),
(4,'Email',1),
(5,'Security',1),
(6,'Other',1);

-- ============================================
-- PRIORITIES TABLE
-- ============================================

DROP TABLE IF EXISTS priorities;

CREATE TABLE priorities (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    PriorityName VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO priorities (ID, PriorityName) VALUES
(1,'Low'),
(2,'Medium'),
(3,'High'),
(4,'Critical');


-- ============================================
-- STATUSES TABLE
-- ============================================

DROP TABLE IF EXISTS statuses;

CREATE TABLE statuses (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    StatusName VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO statuses (ID, StatusName) VALUES
(1,'Open'),
(2,'Assigned'),
(3,'In Progress'),
(4,'Resolved'),
(5,'Closed');


-- ============================================
-- TICKETS TABLE
-- ============================================

DROP TABLE IF EXISTS tickets;

CREATE TABLE tickets (

    ID INT AUTO_INCREMENT PRIMARY KEY,

    TicketNumber VARCHAR(50) NOT NULL UNIQUE,

    Title VARCHAR(255) NOT NULL,

    Description TEXT NOT NULL,

    CategoryId INT NOT NULL,

    PriorityId INT NOT NULL,

    StatusId INT NOT NULL DEFAULT 1,

    CreatedBy INT NOT NULL,

    AssignedTo INT DEFAULT NULL,

    CreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UpdatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_ticket_category
    FOREIGN KEY (CategoryId)
    REFERENCES categories(ID),

    CONSTRAINT fk_ticket_priority
    FOREIGN KEY (PriorityId)
    REFERENCES priorities(ID),

    CONSTRAINT fk_ticket_status
    FOREIGN KEY (StatusId)
    REFERENCES statuses(ID),

    CONSTRAINT fk_ticket_createdby
    FOREIGN KEY (CreatedBy)
    REFERENCES users(ID),

    CONSTRAINT fk_ticket_assignedto
    FOREIGN KEY (AssignedTo)
    REFERENCES users(ID)

);


-- ============================================
-- SAMPLE TICKETS
-- ============================================

INSERT INTO tickets
(
TicketNumber,
Title,
Description,
CategoryId,
PriorityId,
StatusId,
CreatedBy,
AssignedTo
)
VALUES

(
'TKT100001',
'Printer Not Working',
'Office printer is not printing.',
1,
3,
2,
1,
1
),

(
'TKT100002',
'Internet Connection Lost',
'Unable to access internet.',
3,
2,
1,
1,
NULL
),

(
'TKT100003',
'Email Login Issue',
'Cannot login to company email.',
4,
4,
3,
1,
1
);

-- ============================================
-- TICKET COMMENTS TABLE
-- ============================================

DROP TABLE IF EXISTS ticketcomments;

CREATE TABLE ticketcomments (

    ID INT AUTO_INCREMENT PRIMARY KEY,

    TicketId INT NOT NULL,

    UserId INT NOT NULL,

    Comment TEXT NOT NULL,

    CreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_comment_ticket
    FOREIGN KEY (TicketId)
    REFERENCES tickets(ID)
    ON DELETE CASCADE,

    CONSTRAINT fk_comment_user
    FOREIGN KEY (UserId)
    REFERENCES users(ID)
);

-- ============================================
-- TICKET ATTACHMENTS TABLE
-- ============================================

DROP TABLE IF EXISTS ticketattachments;

CREATE TABLE ticketattachments (

    ID INT AUTO_INCREMENT PRIMARY KEY,

    TicketId INT NOT NULL,

    FileName VARCHAR(255) NOT NULL,

    UploadedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_attachment_ticket
    FOREIGN KEY (TicketId)
    REFERENCES tickets(ID)
    ON DELETE CASCADE
);

-- ============================================
-- NOTIFICATIONS TABLE
-- ============================================

DROP TABLE IF EXISTS notifications;

CREATE TABLE notifications (

    ID INT AUTO_INCREMENT PRIMARY KEY,

    UserId INT NOT NULL,

    Message TEXT NOT NULL,

    IsRead TINYINT(1) DEFAULT 0,

    CreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notification_user
    FOREIGN KEY (UserId)
    REFERENCES users(ID)
);

-- ============================================
-- ACTIVITY LOGS TABLE
-- ============================================

DROP TABLE IF EXISTS activitylogs;

CREATE TABLE activitylogs (

    ID INT AUTO_INCREMENT PRIMARY KEY,

    UserId INT NOT NULL,

    Activity VARCHAR(255) NOT NULL,

    CreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_activity_user
    FOREIGN KEY (UserId)
    REFERENCES users(ID)
);

-- ============================================
-- SAMPLE ACTIVITY
-- ============================================

INSERT INTO activitylogs (UserId, Activity)
VALUES
(1,'Admin account created');

-- ============================================
-- DATABASE CREATED SUCCESSFULLY
-- ============================================