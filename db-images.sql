# -- German federal state `state_archuves` may or may not have named collections in which parish registers are organized. If they don't, then all its
# -- parish localities will have an empty string for the collection path.
# -- TODO: Rework this now that I added the 'uses_collects' flag. 
# -- select CONCAT(arhives.archive_path, ' > ', orts.ort, ' > ', registers.register, ' > ', images.image_no)
# --  from
# --      archives
# --    join
# --      archive_orts as orts
# --         on archives.id=orts.archive.id
# --    join 
# --      church_registers as registers
# --         on orts.id=registers.ort_id
# --    join
# --      cited_images as images
# --         on registers.id=image.register_id; 
 
create table if not exists state_archives (
    id int auto_increment not null primary key,
    bundesland varchar(50) not null,
    archive_name varchar(50) not null,
    uses_collects boolean not null,
    unique(bundesland),
    unique(archive_name),
) engine = INNODB;

c
# -- collection_paths 
# --

create table if not exists collection_paths (
   id int auto_increment not null primary key,
   path varchar(100) not null,
   archive_id int not null,
   unique(name),
   foreign key(archive_id) references archive_paths(id)
);

# -- The parishes or Kirchspiele with registers are designated by the locality named `name`
# -- within which they are located. They have a collection path, which is possibly empty

create table parish_localities (
  id int not null auto_increment primary key,
  name varchar(50) not null,
  collection_id int not null,
  archive_id int not null,
  unique(name),
  foreign key (collection_id) references collection_paths(id),
  foreign key(archive_id) references state_archives(id)
) engine=INNODB;


# -- Name of the actual volume or`register` name, which usually corresponds to a date range and types of 
# -- ceremonies or events, like births, recorded. The register named `register` is likely unique -- since it is named according
# -- to the ceremonies it records and their date range -- but we can't quarantee this.

create table parish_registers (
   id int not null auto_increment primary key,
   name varchar(100) not null,
   parish_id int not null,
   unique(name, parish_id),
   foreign key(parish_id) references parish_localities(id) 
) engine=INNODB;


# -- The images from a given registers, along with the archion URL for viewing
# -- the image. A BGS-compliant citation with a navigation 'path' to the image, along with its
# -- url for viewin,  will be created by joining the prior tables with this table.

create table if not exists register_images (
    id int not null auto_increment primary key,
    register_id int not null,
    url varchar(70) not null,
    image_no int not null,
    unique (register_id, image_no),
    unique (url),
    foreign key(register_id) references parish_registers(id)
) engine = INNODB;

# -- Recorded Events

# -- All recorded church ceremonies for a particular person or persons that have been recorded on date,
# -- whose event type or ceremony type is, whose pastor's person's key is pastor_id; and which appear
# -- on a particular register's image number register_img_id, a reference to the register_images.id key.
# -- that has occurs on a image of a paritcular register set of pagesa.

create table if not exists adoc_files (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    file_name vchar(45) not null,
    module_name vchar(35) not null,
) enginen=INNODB;

create table if not exists recorded_events (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    date DATE NOT NULL,
    event ENUM(
        'birth',
        'baptism',
        'confirmation',
        'proclamation',
        'marriage',
        'death',
        'burial'
    ) NOT NULL,
    image_id INT NOT NULL,
    fnames vchar(45) not null,
    surname vchar(35) not null,
    foreign key(image_id) references register_images(id),
    foreign key(adoc_id) references adoc_files(id)
) engine = INNODB;
