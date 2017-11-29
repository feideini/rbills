create table properties (id char(2),fullLabel varchar(99),shortLabel varchar(99),addressLine varchar(99),municipality varchar(99),province char(2),postalCode char(7));
create table rentalUnits (id char(3),property char(2),fullLabel varchar(99),shortLabel varchar(99),addressLine varchar(99),municipality varchar(99),province char(2),postalCode char(7));
create table renters (id char(4),fullName varchar(99),shortName varchar(99),rentalUnit char(3),rentAmount decimal(10,2),percentageTerasen decimal(5,4),percentageBchydro decimal(5,4),firstMonth int);
create table utilities (property char(2),month int,companyName varchar(99),amount decimal(10,2));
create table stateItems (renterid char(4),month int,day int,code char(3),amount decimal(10,2));
create table login (id char(4),fullName varchar(99),username varchar(99),password varchar(99));
create table months (monthNumber int,monthString char(7));
