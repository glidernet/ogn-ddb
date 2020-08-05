use glidernet_devicesdb;
-- SELECT count(*)  as cnt , usr_adress FROM devices, users where dev_userid = usr_id group by usr_id;
 SELECT air_id, air_acreg, air_accn FROM trackedobjects, devices WHERE air_id = dev_flyobj AND (SELECT count(*) from devices WHERE dev_flyobj = air_id) = 0;
