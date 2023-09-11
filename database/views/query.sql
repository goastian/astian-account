DROP VIEW IF EXISTS calendar;
CREATE VIEW calendar AS (
	SELECT 
	calendars.id , 
	calendars.`day` , 
	calendars.available , 
	calendars.category_id, 
	categories.name  AS category_name 
	FROM calendars 
	INNER JOIN categories ON calendars.category_id = categories.id
);

DROP VIEW  IF EXISTS bookings;
CREATE VIEW bookings AS (
	SELECT 
	   b.id, b.code, b.check_in, b.check_out , b.created_at, b.updated_at , b.`type` , 
	   c.name , c.last_name , c.`number`,c.email , c.phone 
	FROM  booking b 
	LEFT JOIN clients c ON c.id  = b.client_id 
	WHERE b.deleted_at IS NULL 
);
 
DROP VIEW IF EXISTS room;
CREATE VIEW room AS (
	SELECT 
		rooms.id, 
		rooms.`number` , 
		rooms.`capacity` , 
		rooms.description,
		rooms.note,
		rooms.deleted_at , 
		rooms.created_at , 
		rooms.updated_at,
		rooms.category_id , 
		categories.name  as category_name
	FROM rooms 
	INNER JOIN categories ON rooms.category_id = categories.id 
);


 
