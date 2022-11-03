use master
drop database Pedal
create database Pedal
go
use Pedal
go

create table tickets(ticket_number int not null primary key, ticket_status varchar(50) not null,
                     bike_make varchar(50) not null, bike_model varchar(50) not null,
                     bike_color varchar(50) not null, bike_other varchar(500) not null,
                     cwid varchar(50) not null,
                     fname varchar(50) not null, lname varchar(50) not null,
                     phone_number varchar(50) not null, email varchar(50) not null,
                     datetime_in datetime not null, datetime_quote datetime,
                     datetime_complete datetime, datetime_out datetime, quote_under_30 char not null,
                     customer_comments varchar(500), mechanic_comments varchar(500));

create table work_requested(ticket_number int not null foreign key references tickets(ticket_number),
                            work_description varchar(500) not null,
                            CONSTRAINT work_requested_composit_pk primary key clustered(ticket_number, work_description));                    

create table work_done(ticket_number int not null foreign key references tickets(ticket_number),
                       work_description varchar(500) not null, parts float not null,
                       labor float not null,
                       CONSTRAINT work_done_composit_pk primary key clustered(ticket_number, work_description));