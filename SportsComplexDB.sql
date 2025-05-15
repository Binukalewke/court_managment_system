-- Create the database
CREATE DATABASE IF NOT EXISTS SportsComplexDB;
USE SportsComplexDB;

-- 1. UserRoles Table: Stores roles for users (e.g., Admin, Player)
CREATE TABLE UserRoles (
    RoleID INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each role
    RoleName VARCHAR(50) NOT NULL UNIQUE   -- Name of the role (e.g., Admin, Player)
);

-- 2. Users Table: Stores user account information
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each user
    Username VARCHAR(50) NOT NULL UNIQUE,   -- Username for login
    PasswordHash VARCHAR(255) NOT NULL,     -- Hashed password for security
    FullName VARCHAR(100) NOT NULL,         -- Full name of the user
    Email VARCHAR(100) NOT NULL UNIQUE,     -- Email address of the user
    Phone VARCHAR(15),                      -- Phone number of the user
    RoleID INT,                             -- Role of the user (linked to UserRoles table)
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of account creation
    FOREIGN KEY (RoleID) REFERENCES UserRoles(RoleID) -- Links to UserRoles table
);

-- 3. Courts Table: Stores information about available courts
CREATE TABLE Courts (
    CourtID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each court
    CourtName VARCHAR(100) NOT NULL,       -- Name of the court (e.g., Court A, Court B)
    SportType VARCHAR(50) NOT NULL,        -- Type of sport played on the court (e.g., Basketball, Badminton)
    AvailabilityStatus ENUM('Available', 'Booked', 'Under Maintenance') DEFAULT 'Available' -- Availability status of the court
);

-- 4. BookingStatus Table: Tracks the status of bookings (e.g., Pending, Confirmed, Canceled, Postponed)
CREATE TABLE BookingStatus (
    StatusID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each status
    StatusName VARCHAR(50) NOT NULL UNIQUE  -- Name of the status (e.g., Pending, Confirmed, Canceled)
);

-- 5. Bookings Table: Manages court bookings made by users
CREATE TABLE Bookings (
    BookingID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each booking
    UserID INT,                              -- ID of the user who made the booking
    CourtID INT,                             -- ID of the court being booked
    BookingDate DATE NOT NULL,               -- Date of the booking
    StartTime TIME NOT NULL,                 -- Start time of the booking
    EndTime TIME NOT NULL,                   -- End time of the booking
    StatusID INT DEFAULT 1,                  -- Status of the booking (default is Pending)
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of booking creation
    FOREIGN KEY (UserID) REFERENCES Users(UserID), -- Links to Users table
    FOREIGN KEY (CourtID) REFERENCES Courts(CourtID), -- Links to Courts table
    FOREIGN KEY (StatusID) REFERENCES BookingStatus(StatusID) -- Links to BookingStatus table
);

-- 6. PaymentMethods Table: Stores available payment methods (e.g., Credit Card, Mobile Wallet)
CREATE TABLE PaymentMethods (
    PaymentMethodID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each payment method
    MethodName VARCHAR(50) NOT NULL UNIQUE         -- Name of the payment method (e.g., Credit Card, Mobile Wallet)
);

-- 7. Payments Table: Stores payment details for bookings
CREATE TABLE Payments (
    PaymentID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each payment
    BookingID INT,                           -- ID of the booking associated with the payment
    Amount DECIMAL(10, 2) NOT NULL,          -- Amount paid for the booking
    PaymentMethodID INT,                     -- ID of the payment method used (linked to PaymentMethods table)
    PaymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of the payment
    FOREIGN KEY (BookingID) REFERENCES Bookings(BookingID), -- Links to Bookings table
    FOREIGN KEY (PaymentMethodID) REFERENCES PaymentMethods(PaymentMethodID) -- Links to PaymentMethods table
);

-- 8. Feedback Table: Stores user feedback about courts
CREATE TABLE Feedback (
    FeedbackID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each feedback entry
    UserID INT,                               -- ID of the user providing feedback
    CourtID INT,                              -- ID of the court being reviewed
    Rating INT CHECK (Rating BETWEEN 1 AND 5), -- Rating given by the user (1 to 5 stars)
    Comment TEXT,                             -- Optional comment from the user
    FeedbackDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of the feedback
    FOREIGN KEY (UserID) REFERENCES Users(UserID), -- Links to Users table
    FOREIGN KEY (CourtID) REFERENCES Courts(CourtID) -- Links to Courts table
);

-- Insert default roles into UserRoles table
INSERT INTO UserRoles (RoleName) VALUES ('Admin'), ('Player');

-- Insert default payment methods into PaymentMethods table
INSERT INTO PaymentMethods (MethodName) VALUES ('Credit Card'), ('Mobile Wallet'), ('Cash');

-- Insert default booking statuses into BookingStatus table
-- Pending is the default status for new bookings
INSERT INTO BookingStatus (StatusName) VALUES ('Pending'), ('Confirmed'), ('Canceled'), ('Postponed');