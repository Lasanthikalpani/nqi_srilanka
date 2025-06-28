package ui;

import model.Airplane.Booking;
import model.User.User;
import service.BookingService;
import java.awt.BorderLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.SQLException;
import java.util.List;
import javax.swing.*;
import javax.swing.table.DefaultTableModel; // Import DefaultTableModel

// Assuming CustomerDashboard extends JFrame or JPanel
public class CustomerDashboard extends JFrame { // Or JPanel, depending on your setup

    private User loggedInUser; // To store the logged-in user object
    private JTable bookingsTable;
    private DefaultTableModel tableModel;
    private JButton viewBookingsButton;

    // Constructor with User object
    public CustomerDashboard(User user) {
        this.loggedInUser = user; // Store the logged-in user
        setTitle("Customer Dashboard");
        setSize(800, 600);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null); // Center the window

        initComponents(); // Initialize UI components
        // displayMyBookings(user.getId()); // You might call it here if you want to display on load
    }

    private void initComponents() {
        // ... (other UI component initializations)

        viewBookingsButton = new JButton("View My Bookings");
        // Add this button to your layout (e.g., a JPanel for controls)
        JPanel controlPanel = new JPanel();
        controlPanel.add(viewBookingsButton);
        add(controlPanel, BorderLayout.NORTH); // Example placement

        // Initialize JTable and JScrollPane
        String[] columnNames = {"Booking ID", "Flight ID", "Seat Class", "Passengers", "Total Fare", "Booking Date", "Status", "Booked By"};
        tableModel = new DefaultTableModel(columnNames, 0);
        bookingsTable = new JTable(tableModel);
        JScrollPane scrollPane = new JScrollPane(bookingsTable);
        add(scrollPane, BorderLayout.CENTER); // Add the table to the center

        // Action Listener for the "View My Bookings" button
        viewBookingsButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                // Now you can safely get the userId from the stored loggedInUser object
                if (loggedInUser != null) {
                    displayMyBookings(loggedInUser.getId());
                } else {
                    JOptionPane.showMessageDialog(CustomerDashboard.this,
                            "User not logged in. Cannot display bookings.",
                            "Error", JOptionPane.ERROR_MESSAGE);
                }
            }
        });

        // ... (add other customer-specific UI elements)
        setVisible(true);
    }

    private void displayMyBookings(int userId) {
        try {
            tableModel.setRowCount(0); // Clear existing data

            BookingService bookingService = new BookingService();
            List<Booking> bookings = bookingService.getBookingsForUser(userId);

            if (bookings.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No bookings found for this user.", "Information", JOptionPane.INFORMATION_MESSAGE);
            } else {
                for (Booking booking : bookings) {
                    String bookedByName = (booking.getUser() != null && booking.getUser().getProfile() != null)
                                          ? booking.getUser().getProfile().getFullName()
                                          : "N/A";

                    Object[] rowData = {
                        booking.getBookingId(),
                        booking.getFlight().getId(),
                        booking.getSeatClass(),
                        booking.getNumberOfPassengers(),
                        booking.getTotalFare(),
                        booking.getBookingDate(),
                        booking.getBookingStatus(),
                        bookedByName
                    };
                    tableModel.addRow(rowData);
                }
            }
        } catch (SQLException e) {
            JOptionPane.showMessageDialog(this, "Error retrieving bookings: " + e.getMessage(), "Database Error", JOptionPane.ERROR_MESSAGE);
            // Log the error using your logger (e.g., SLF4J, Log4j)
            // LOGGER.error("Error retrieving bookings for user ID {}: {}", userId, e.getMessage(), e);
        }
    }
}