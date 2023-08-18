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
	   booking.id , 
	   booking.created_at as check_in, 
	   booking.check_out ,
	   booking.code,
	   rooms.`number` as room,
	   categories.name as category,
	   companies.company,
	   companies.ruc 
	FROM  rents
	JOIN booking ON booking.id = rents.booking_id 
	JOIN rooms ON rooms.id  = rents.room_id 
	JOIN categories ON categories.id = rents.category_id
	LEFT JOIN companies ON companies.id = booking.company_id 
	WHERE booking.deleted_at IS NULL 
);
 
DROP VIEW IF EXISTS room;
CREATE VIEW room AS (
	SELECT 
		rooms.id, 
		rooms.`number` , 
		rooms.description ,
		rooms.deleted_at , 
		rooms.created_at , 
		rooms.updated_at,
		rooms.category_id , 
		categories.name  as category_name
	FROM rooms 
	INNER JOIN categories ON rooms.category_id = categories.id 
);


 
