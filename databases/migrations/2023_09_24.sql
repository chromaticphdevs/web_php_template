drop table if exists intents;
create table intents(
    id int(10) not null primary key auto_increment,
    category varchar(100),
    intent_value text,

    intent_status varchar(50),
    errors varchar(150),
    message varchar(150),
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now()
);