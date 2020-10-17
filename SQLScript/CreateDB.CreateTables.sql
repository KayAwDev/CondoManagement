CREATE DATABASE TempCondo
GO
Use TempCondo
GO
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
           WHERE TABLE_NAME = N'Employees')
BEGIN
  CREATE TABLE Employees (
    Emp_Username varchar(100) NOT NULL CONSTRAINT PK_Employees PRIMARY KEY,
	Emp_Password nvarchar(100) NOT NULL,
	Emp_Name nvarchar(200) NOT NULL,
    Emp_Level int NOT NULL,
	CreatedDateTime smalldatetime NOT NULL
	);
END
GO
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
    WHERE TABLE_NAME = N'LevelCode')
BEGIN
  CREATE TABLE LevelCode (
    LevelCode int NOT NULL,
    LevelDesc varchar(40) NOT NULL
	);
END
GO
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
           WHERE TABLE_NAME = N'Units')
BEGIN
  CREATE TABLE Units (
    UnitID bigint NOT NULL CONSTRAINT PK_Units PRIMARY KEY IDENTITY(1,1),
    [Block] nvarchar(50) NOT NULL,
	UnitNumber nvarchar(100) NOT NULL,
	UnitOwner nvarchar(200) NOT NULL,
	Owner_ContactNumber nvarchar(100) NOT NULL,
	CreatedDateTime smalldatetime NOT NULL
	);
END
GO
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
           WHERE TABLE_NAME = N'Tenants')
BEGIN
  CREATE TABLE Tenants (
    TenantID bigint NOT NULL CONSTRAINT PK_Tenants PRIMARY KEY IDENTITY(1,1),
    Tenant_Name nvarchar(200) NOT NULL,
	Tenant_ContactNumber nvarchar(100) NOT NULL,
	Tenant_UnitID bigint NOT NULL CONSTRAINT FK_TenantUnit FOREIGN KEY REFERENCES Units(UnitID),
	CreatedDateTime smalldatetime NOT NULL
	);
END
GO
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
           WHERE TABLE_NAME = N'VisitorLog')
BEGIN
  CREATE TABLE VisitorLog (
    VisitorLogID bigint NOT NULL CONSTRAINT PK_VisitorLog PRIMARY KEY IDENTITY(1,1),
    Visitor_Name nvarchar(200) NOT NULL,
	Visitor_ContactNumber nvarchar(100) NOT NULL,
	Visitor_NRIC nvarchar(15) NOT NULL,
	VisitPlace varchar(50) NOT NULL,
	Visit_UnitID bigint NULL CONSTRAINT FK_VisitorUnit FOREIGN KEY REFERENCES Units(UnitID),
	EnterDateTime smalldatetime NOT NULL,
	ExitDateTime smalldatetime NULL
	);
END
GO
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_NAME = N'WebProgram')
BEGIN
  CREATE TABLE WebProgram (
    ProgramName varchar(40) NOT NULL CONSTRAINT PK_WebProgram PRIMARY KEY,
    ParentProgramName varchar(40) NULL,
	MenuName nvarchar(50) NULL,
	MenuSequence smallint NULL,
    Active bit NULL
	);
END
GO
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
    WHERE TABLE_NAME = N'WebSecurity')
BEGIN
  CREATE TABLE WebSecurity (
    Emp_Level int NOT NULL,
    ProgramName varchar(40) NOT NULL,
	Allow bit NULL,
	PRIMARY KEY (Emp_Level, ProgramName)
	);
END
GO
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
           WHERE TABLE_NAME = N'ApiAuth')
BEGIN
  CREATE TABLE ApiAuth (
    id int NOT NULL CONSTRAINT PK_ApiAuth PRIMARY KEY IDENTITY(1,1),
	username nvarchar(100) NOT NULL,
	apiKey varchar(300) NOT NULL,
	createdAt smalldatetime NOT NULL
	);
END
GO
ALTER TABLE ApiAuth ADD  DEFAULT (getdate()) FOR createdAt
