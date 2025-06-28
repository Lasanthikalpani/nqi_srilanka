/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package dao;

import dao.IUserDAO; // Assuming you have a UserDAO interface and implementation
import dao.UserDAOImpl; // Assuming you have UserDAOImpl
import dao.FlightDAO;
import model.Airplane.Booking;
import model.Airplane.Flight;
import db.DBConnection;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.time.LocalDateTime;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;
import model.Airplane.Airplane;
import model.Airplane.Airport;
import model.User.User;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

/**
 *
 * @author lasan
 */
public class BookingDAOImpl implements BookingDAO {

    private final IUserDAO userDAO;
    private final FlightDAO flightDAO;
    private static final Logger LOGGER = LoggerFactory.getLogger(BookingDAOImpl.class);
    private final AirportDAO airportDAO;
    private final AirplaneDAO airplaneDAO;

    public BookingDAOImpl(IUserDAO userDAO, FlightDAO flightDAO, AirportDAO airportDAO, AirplaneDAO airplaneDAO) {
        if (userDAO == null) {
            throw new IllegalArgumentException("UserDAO cannot be null.");
        }
        if (flightDAO == null) {
            throw new IllegalArgumentException("FlightDAO cannot be null.");
        }
        if (airportDAO == null) {
            throw new IllegalArgumentException("AirportDAO cannot be null.");
        }
        if (airplaneDAO == null) {
            throw new IllegalArgumentException("AirplaneDAO cannot be null.");
        }
        this.userDAO = userDAO;
        this.flightDAO = flightDAO;
        this.airportDAO = airportDAO;
        this.airplaneDAO = airplaneDAO;
    }

    public BookingDAOImpl() {
        this.userDAO = new UserDAOImpl();
        this.flightDAO = new FlightDAO();
        this.airportDAO = new AirportDAO();
        this.airplaneDAO = new AirplaneDAO();
    }

    @Override
    public int getBookedSeatsForFlightAndClass(int flightId, String seatClass) throws SQLException {
        String sql = "SELECT COALESCE(SUM(number_of_passengers), 0) AS booked_count FROM bookings "
                + "WHERE flight_id = ? AND seat_class = ? AND booking_status = 'CONFIRMED'";

        Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        int bookedCount = 0;

        try {
            conn = getConnection();
            pstmt = conn.prepareStatement(sql);
            pstmt.setInt(1, flightId);
            pstmt.setString(2, seatClass);
            rs = pstmt.executeQuery();

            if (rs.next()) {
                bookedCount = rs.getInt("booked_count");
            }
        } catch (SQLException e) {
            LOGGER.error("Error getting booked seats for flight ID {} and class {}: {}", flightId, seatClass, e.getMessage(), e);
            throw e;
        } finally {
            closeResources(rs, pstmt, conn);
        }
        return bookedCount;
    }

    private Connection getConnection() throws SQLException {
        return DBConnection.getInstance().getConnection();
    }

    private void closeResources(ResultSet rs, PreparedStatement pstmt, Connection conn) {
        try {
            if (rs != null) {
                rs.close();
            }
        } catch (SQLException e) {
            LOGGER.warn("Error closing ResultSet: {}", e.getMessage(), e);
        }
        try {
            if (pstmt != null) {
                pstmt.close();
            }
        } catch (SQLException e) {
            LOGGER.warn("Error closing PreparedStatement: {}", e.getMessage(), e);
        }
        try {
            if (conn != null) {
                conn.close();
            }
        } catch (SQLException e) {
            LOGGER.warn("Error closing Connection: {}", e.getMessage(), e);
        }
    }

    @Override
    public boolean saveBooking(Booking booking, Connection conn) throws SQLException {
        String insertBookingSQL = "INSERT INTO bookings (user_id, flight_id, seat_class, number_of_passengers, total_fare, booking_date, booking_status, created_at, updated_at) "
                + "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        String updateFlightCapacitySQL;
        switch (booking.getSeatClass().toLowerCase()) {
            case "first":
                updateFlightCapacitySQL = "UPDATE flights SET first_class_capacity = first_class_capacity - ? WHERE id = ?";
                break;
            case "business":
                updateFlightCapacitySQL = "UPDATE flights SET business_class_capacity = business_class_capacity - ? WHERE id = ?";
                break;
            case "economy":
                updateFlightCapacitySQL = "UPDATE flights SET economy_class_capacity = economy_class_capacity - ? WHERE id = ?";
                break;
            default:
                LOGGER.error("Invalid seat class provided for booking: {}", booking.getSeatClass());
                throw new IllegalArgumentException("Invalid seat class: " + booking.getSeatClass());
        }

        PreparedStatement bookingPstmt = null;
        PreparedStatement flightUpdatePstmt = null;
        ResultSet generatedKeys = null;

        try {
            bookingPstmt = conn.prepareStatement(insertBookingSQL, PreparedStatement.RETURN_GENERATED_KEYS);
            bookingPstmt.setInt(1, booking.getUser().getId());
            bookingPstmt.setInt(2, booking.getFlight().getId());
            bookingPstmt.setString(3, booking.getSeatClass());
            bookingPstmt.setInt(4, booking.getNumberOfPassengers());
            bookingPstmt.setBigDecimal(5, booking.getTotalFare());
            bookingPstmt.setTimestamp(6, Timestamp.valueOf(booking.getBookingDate()));
            bookingPstmt.setString(7, booking.getBookingStatus());
            bookingPstmt.setTimestamp(8, Timestamp.valueOf(booking.getCreatedAt()));
            bookingPstmt.setTimestamp(9, Timestamp.valueOf(booking.getUpdatedAt()));

            int affectedRows = bookingPstmt.executeUpdate();

            if (affectedRows == 0) {
                LOGGER.warn("Creating booking failed, no rows affected for user ID: {} flight ID: {}",
                        booking.getUser().getId(), booking.getFlight().getId());
                return false;
            }

            generatedKeys = bookingPstmt.getGeneratedKeys();
            if (generatedKeys.next()) {
                booking.setBookingId(generatedKeys.getInt(1));
                LOGGER.info("Booking inserted with ID: {}", booking.getBookingId());
            } else {
                LOGGER.error("Creating booking failed, no ID obtained for user ID: {} flight ID: {}",
                        booking.getUser().getId(), booking.getFlight().getId());
                return false;
            }

            flightUpdatePstmt = conn.prepareStatement(updateFlightCapacitySQL);
            flightUpdatePstmt.setInt(1, booking.getNumberOfPassengers());
            flightUpdatePstmt.setInt(2, booking.getFlight().getId());

            int updatedFlightRows = flightUpdatePstmt.executeUpdate();
            if (updatedFlightRows == 0) {
                LOGGER.warn("Updating flight capacity failed, no rows affected for flight ID: {}", booking.getFlight().getId());
                return false;
            }
            LOGGER.info("Flight capacity updated successfully for flight ID: {}", booking.getFlight().getId());
            return true;

        } catch (SQLException e) {
            LOGGER.error("SQL Error during saveBooking transaction: {}", e.getMessage(), e);
            throw e;
        } finally {
            try {
                if (generatedKeys != null) {
                    generatedKeys.close();
                }
            } catch (SQLException e) {
                LOGGER.warn("Error closing generatedKeys ResultSet: {}", e.getMessage(), e);
            }
            try {
                if (bookingPstmt != null) {
                    bookingPstmt.close();
                }
            } catch (SQLException e) {
                LOGGER.warn("Error closing booking PreparedStatement: {}", e.getMessage(), e);
            }
            try {
                if (flightUpdatePstmt != null) {
                    flightUpdatePstmt.close();
                }
            } catch (SQLException e) {
                LOGGER.warn("Error closing flight update PreparedStatement: {}", e.getMessage(), e);
            }
        }
    }

    @Override // It's good practice to add @Override annotation
    public List<Booking> getBookingsByUserIdAndCustomerDetails(Connection conn, int userId, String fullName, String contactInfo) throws SQLException {
        List<Booking> bookings = new java.util.ArrayList<>();
        StringBuilder sqlBuilder = new StringBuilder();

        sqlBuilder.append("SELECT ")
                .append("b.booking_id, b.user_id, b.flight_id, b.seat_class, b.number_of_passengers, ")
                .append("b.total_fare, b.booking_status, b.booking_date, b.created_at, b.updated_at, ")
                .append("f.id AS flight_table_id, f.flight_number, f.departure_airport, f.arrival_airport, ") // Add f.id alias
                .append("f.departure_time, f.arrival_time, ")
                .append("f.assigned_airplane_id, ")
                .append("f.first_class_capacity, f.business_class_capacity, ")
                .append("f.economy_class_capacity, f.price, ")
                .append("u.username, u.full_name, u.email, u.phone, ")
                .append("da.id AS departure_airport_id, da.name AS departure_airport_name, da.iata_code AS departure_iata_code, ") // ADDED da.id AS departure_airport_id
                .append("aa.id AS arrival_airport_id, aa.name AS arrival_airport_name, aa.iata_code AS arrival_iata_code ") // ADDED aa.id AS arrival_airport_id
                .append("FROM bookings b ")
                .append("JOIN flights f ON b.flight_id = f.id ")
                .append("JOIN users u ON b.user_id = u.id ")
                .append("JOIN airports da ON f.departure_airport = da.id ")
                .append("JOIN airports aa ON f.arrival_airport = aa.id ");

        boolean firstCondition = true;
        sqlBuilder.append(" WHERE b.user_id = ?");
        firstCondition = false;

        if (fullName != null && !fullName.trim().isEmpty()) {
            sqlBuilder.append(" AND u.full_name LIKE ?");
        }

        if (contactInfo != null && !contactInfo.trim().isEmpty()) {
            sqlBuilder.append(" AND u.phone LIKE ?");
        }

        try (PreparedStatement pstmt = conn.prepareStatement(sqlBuilder.toString())) {
            int paramIndex = 1;
            pstmt.setInt(paramIndex++, userId);

            if (fullName != null && !fullName.trim().isEmpty()) {
                pstmt.setString(paramIndex++, "%" + fullName + "%");
            }
            if (contactInfo != null && !contactInfo.trim().isEmpty()) {
                pstmt.setString(paramIndex++, "%" + contactInfo + "%");
            }

            try (ResultSet rs = pstmt.executeQuery()) {
                while (rs.next()) {
                    bookings.add(createBookingFromResultSet(rs));
                }
            }
        } catch (SQLException e) {
            System.err.println("SQL Error in getBookingsByUserIdAndCustomerDetails: " + e.getMessage());
            e.printStackTrace();
            throw e;
        }
        return bookings;
    }

    @Override
    public List<Booking> getAllBookings(Connection conn) throws SQLException {
        List<Booking> bookings = new ArrayList<>();
        String sql = "SELECT "
                + "b.id AS booking_id, b.user_id, b.flight_id, b.booking_date, b.status AS booking_status, b.seat_class, "
                + "b.number_of_passengers, b.total_fare, b.created_at, b.updated_at, "
                + "f.id AS flight_table_id, f.flight_number, f.departure_time, f.arrival_time, f.first_class_capacity, " // Added f.id alias
                + "f.business_class_capacity, f.economy_class_capacity, f.assigned_airplane_id, f.price, "
                + "da.id AS departure_airport_id, da.name AS departure_airport_name, " // Explicitly alias da.id
                + "da.location AS departure_airport_location, da.iata_code AS departure_airport_iata_code, "
                + "da.country AS departure_airport_country, "
                + "aa.id AS arrival_airport_id, aa.name AS arrival_airport_name, " // Explicitly alias aa.id
                + "aa.location AS arrival_airport_location, aa.iata_code AS arrival_airport_iata_code, "
                + "aa.country AS arrival_airport_country, "
                + "a.id AS airplane_id, a.model AS airplane_model, a.capacity_class AS airplane_capacity_class, "
                + "a.current_location AS airplane_current_location, a.status AS airplane_status, "
                + "u.id AS user_table_id, u.username, u.full_name, u.email, u.phone "
                + "FROM Bookings b "
                + "JOIN Flights f ON b.flight_id = f.id "
                + "JOIN Airports da ON f.departure_airport = da.id "
                + "JOIN Airports aa ON f.arrival_airport = aa.id "
                + "JOIN Airplanes a ON f.assigned_airplane_id = a.id "
                + "JOIN Users u ON b.user_id = u.id";

        try (PreparedStatement pstmt = conn.prepareStatement(sql); ResultSet rs = pstmt.executeQuery()) {
            while (rs.next()) {
                bookings.add(createBookingFromResultSet(rs));
            }
        } catch (SQLException e) {
            LOGGER.error("Error retrieving all bookings: {}", e.getMessage(), e);
            throw e;
        }
        return bookings;
    }

    private Booking createBookingFromResultSet(ResultSet rs) throws SQLException {
        // Booking details
        int bookingId = rs.getInt("booking_id");
        LocalDateTime bookingDate = rs.getTimestamp("booking_date").toLocalDateTime();
        String bookingStatus = rs.getString("booking_status");
        String seatClass = rs.getString("seat_class");
        int numberOfPassengers = rs.getInt("number_of_passengers"); // Ensure this is read

        // User
        int userId = rs.getInt("user_id"); // Or rs.getInt("user_table_id") if aliased in query
        User user = userDAO.getUserById(userId); // Still a new connection for this if userDAO isn't refactored

        // Flight details
        // Get flight ID using the alias from the JOINed flight table
        int flightIdFromBooking = rs.getInt("flight_id"); // This is from the bookings table
        // Use aliased 'flight_table_id' if you need the actual flight ID from the flights table:
        // int flightIdFromFlightTable = rs.getInt("flight_table_id");
        String flightNumber = rs.getString("flight_number");

        // Airport objects - NOW USING THE ALIASES 'departure_airport_id' and 'arrival_airport_id'
        int departureAirportId = rs.getInt("departure_airport_id"); // FIX: Using the alias
        Airport departureAirport = airportDAO.getAirportById(departureAirportId); // Still a new connection for this

        int arrivalAirportId = rs.getInt("arrival_airport_id"); // FIX: Using the alias
        Airport arrivalAirport = airportDAO.getAirportById(arrivalAirportId); // Still a new connection for this

        // Times
        LocalDateTime flightDepartureTime = rs.getTimestamp("departure_time").toLocalDateTime();
        LocalDateTime flightArrivalTime = rs.getTimestamp("arrival_time").toLocalDateTime();

        // Assigned Airplane
        int assignedAirplaneId = rs.getInt("assigned_airplane_id");
        Airplane assignedAirplane = airplaneDAO.findAirplaneById(assignedAirplaneId); // Still a new connection for this

        // Capacities and Price
        int firstClassCapacity = rs.getInt("first_class_capacity");
        int businessClassCapacity = rs.getInt("business_class_capacity");
        int economyClassCapacity = rs.getInt("economy_class_capacity");
        java.math.BigDecimal priceBigDecimal = rs.getBigDecimal("price");
        double price = priceBigDecimal != null ? priceBigDecimal.doubleValue() : 0.0;

        Flight flight = new Flight(
                flightIdFromBooking, // Using the flight_id from the booking record
                flightNumber,
                departureAirport,
                arrivalAirport,
                flightDepartureTime,
                flightArrivalTime,
                assignedAirplane,
                firstClassCapacity,
                businessClassCapacity,
                economyClassCapacity,
                price
        );

        return new Booking(
                bookingId,
                user,
                flight,
                seatClass,
                numberOfPassengers, // Use the variable
                rs.getBigDecimal("total_fare"),
                bookingStatus
        );
    }

    @Override // It's good practice to add @Override annotation
    public Booking getBookingById(int id) throws SQLException {
        Booking booking = null;
        String sql = "SELECT "
                + "b.id AS booking_id, b.user_id, b.flight_id, b.booking_date, b.status AS booking_status, b.seat_class, "
                + "b.number_of_passengers, b.total_fare, b.created_at, b.updated_at, "
                + "f.id AS flight_table_id, f.flight_number, f.departure_time, f.arrival_time, f.first_class_capacity, "
                + "f.business_class_capacity, f.economy_class_capacity, f.assigned_airplane_id, f.price, "
                + "da.id AS departure_airport_id, da.name AS departure_airport_name, "
                + "da.location AS departure_airport_location, da.iata_code AS departure_iata_code, "
                + "da.country AS departure_airport_country, "
                + "aa.id AS arrival_airport_id, aa.name AS arrival_airport_name, "
                + "aa.location AS arrival_airport_location, aa.iata_code AS arrival_airport_iata_code, "
                + "aa.country AS arrival_airport_country, "
                + "a.id AS airplane_id, a.model AS airplane_model, a.capacity_class AS airplane_capacity_class, "
                + "a.current_location AS airplane_current_location, a.status AS airplane_status, "
                + "u.id AS user_table_id, u.username, u.full_name, u.email, u.phone "
                + "FROM Bookings b "
                + "JOIN Flights f ON b.flight_id = f.id "
                + "JOIN Airports da ON f.departure_airport = da.id "
                + "JOIN Airports aa ON f.arrival_airport = aa.id "
                + "JOIN Airplanes a ON f.assigned_airplane_id = a.id "
                + "JOIN Users u ON b.user_id = u.id "
                + "WHERE b.id = ?";

        Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;

        try {
            conn = getConnection();
            pstmt = conn.prepareStatement(sql);
            pstmt.setInt(1, id);
            rs = pstmt.executeQuery();

            if (rs.next()) {
                booking = createBookingFromResultSet(rs);
            }
        } catch (SQLException e) {
            LOGGER.error("Error retrieving booking by ID: {}", id, e);
            throw e;
        } finally {
            closeResources(rs, pstmt, conn);
        }
        return booking;
    }
}