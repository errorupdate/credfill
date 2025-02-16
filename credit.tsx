import React, { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select } from '@/components/ui/select';
import { AlertCircle, CheckCircle, CreditCard, Home, Car, Briefcase, Phone } from 'lucide-react';

// Main component
const CreditLoanPage = () => {
  const [currentPage, setCurrentPage] = useState('main');
  const [selectedLoanType, setSelectedLoanType] = useState('');
  const [bookingNumber, setBookingNumber] = useState(null);

  const lendingPartners = [
    'HDFC Bank', 'ICICI Bank', 'Axis Bank', 'SBI', 'Bajaj Finserv'
  ];

  const loanCategories = [
    { name: 'Home Loans', icon: Home },
    { name: 'Car Loans', icon: Car },
    { name: 'Vehicle Loans', icon: Car },
    { name: 'Business Loans', icon: Briefcase },
    { name: 'Bike Loans', icon: Car },
    { name: 'Personal Loans', icon: CreditCard },
    { name: 'Mortgage Loans', icon: Home }
  ];

  const renderMainPage = () => (
    <div className="max-w-6xl mx-auto p-6 space-y-8">
      {/* Hero Section */}
      <div className="text-center space-y-4 py-12">
        <h1 className="text-4xl font-bold text-gray-900">Unlock Your Dreams with the Right Loan</h1>
        <p className="text-xl text-gray-600">Explore Flexible Loan Options Tailored to Your Needs</p>
      </div>

      {/* Why Choose Us Section */}
      <Card className="bg-white">
        <CardHeader>
          <CardTitle>Why Choose Us for Loans</CardTitle>
        </CardHeader>
        <CardContent className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div className="flex items-start space-x-2">
            <CheckCircle className="text-green-500" />
            <p>Hassle-free application process</p>
          </div>
          <div className="flex items-start space-x-2">
            <CheckCircle className="text-green-500" />
            <p>Instant approvals and quick disbursal</p>
          </div>
          <div className="flex items-start space-x-2">
            <CheckCircle className="text-green-500" />
            <p>Transparent interest rates</p>
          </div>
          <div className="flex items-start space-x-2">
            <CheckCircle className="text-green-500" />
            <p>Trusted by thousands of satisfied customers</p>
          </div>
        </CardContent>
      </Card>

      {/* CIBIL Score Section */}
      <Card className="bg-blue-50">
        <CardHeader>
          <CardTitle>Know Your Credit Health</CardTitle>
        </CardHeader>
        <CardContent className="text-center">
          <p className="mb-4">Understanding your CIBIL score is the first step to securing the best loan options. Check your score now, absolutely free!</p>
          <Button variant="default" size="lg">
            Check Your CIBIL Score for Free
          </Button>
        </CardContent>
      </Card>

      {/* Lending Partners Section */}
      <Card>
        <CardHeader>
          <CardTitle>Our Lending Partners</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
            {lendingPartners.map((partner) => (
              <div key={partner} className="p-4 border rounded text-center">
                {partner}
              </div>
            ))}
          </div>
          <p className="mt-4 text-center text-gray-600">
            We are onboarding more and more Banks and NBFCs in the days to come.
          </p>
        </CardContent>
      </Card>

      {/* Loan Categories Section */}
      <Card>
        <CardHeader>
          <CardTitle>Explore Our Loan Categories</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {loanCategories.map(({ name, icon: Icon }) => (
              <Button
                key={name}
                variant="outline"
                className="h-24 flex flex-col items-center justify-center space-y-2"
                onClick={() => {
                  setSelectedLoanType(name);
                  setCurrentPage('loanType');
                }}
              >
                <Icon className="h-6 w-6" />
                <span>{name}</span>
              </Button>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>
  );

  const renderLoanTypePage = () => (
    <div className="max-w-6xl mx-auto p-6 space-y-8">
      <Button onClick={() => setCurrentPage('main')} variant="outline">
        ← Back to Main Page
      </Button>

      <div className="text-center space-y-4 py-12">
        <h1 className="text-4xl font-bold text-gray-900">
          {selectedLoanType} - Make Your Dreams a Reality
        </h1>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <Card>
          <CardHeader>
            <CardTitle>Benefits</CardTitle>
          </CardHeader>
          <CardContent className="space-y-2">
            <div className="flex items-center space-x-2">
              <CheckCircle className="text-green-500" />
              <p>Flexible repayment options</p>
            </div>
            <div className="flex items-center space-x-2">
              <CheckCircle className="text-green-500" />
              <p>Attractive interest rates</p>
            </div>
            <div className="flex items-center space-x-2">
              <CheckCircle className="text-green-500" />
              <p>High loan-to-value ratio</p>
            </div>
            <div className="flex items-center space-x-2">
              <CheckCircle className="text-green-500" />
              <p>Tax benefits on principal and interest repayment</p>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Key Details</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div>
              <h3 className="font-semibold">Time Required</h3>
              <p>Typical approval time: 5-7 business days</p>
            </div>
            <div>
              <h3 className="font-semibold">Interest Rate</h3>
              <p>Starting at 6.75% p.a.</p>
            </div>
            <div>
              <h3 className="font-semibold">Processing Fee</h3>
              <p>1-2% of loan amount</p>
            </div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Documents Needed</CardTitle>
        </CardHeader>
        <CardContent>
          <ul className="list-disc pl-6 space-y-2">
            <li>Identity Proof (Aadhaar, PAN, Passport)</li>
            <li>Address Proof (Utility bills, Rent agreement)</li>
            <li>Income Proof (Salary slips, Bank statements)</li>
            <li>Property Documents (Sale deed, Property tax receipt)</li>
          </ul>
        </CardContent>
      </Card>

      <div className="flex justify-center space-x-4">
        <Button
          variant="outline"
          onClick={() => window.open('mailto:support@credfill.com')}
        >
          Chat with Us
        </Button>
        <Button onClick={() => setCurrentPage('booking')}>
          Book Now
        </Button>
      </div>
    </div>
  );

  const renderBookingPage = () => (
    <div className="max-w-md mx-auto p-6 space-y-8">
      <Button onClick={() => setCurrentPage('loanType')} variant="outline">
        ← Back
      </Button>

      <Card>
        <CardHeader>
          <CardTitle>Book Your Loan</CardTitle>
        </CardHeader>
        <CardContent>
          <form className="space-y-4" onSubmit={(e) => {
            e.preventDefault();
            setBookingNumber(Math.floor(Math.random() * 1000000));
            setCurrentPage('confirmation');
          }}>
            <div>
              <label className="block mb-2">Name</label>
              <Input required placeholder="Your full name" />
            </div>
            <div>
              <label className="block mb-2">Phone</label>
              <Input required type="tel" placeholder="Your phone number" />
            </div>
            <div>
              <label className="block mb-2">Email</label>
              <Input required type="email" placeholder="Your email" />
            </div>
            <div>
              <label className="block mb-2">Loan Amount</label>
              <Input required type="number" placeholder="Desired loan amount" />
            </div>
            <div>
              <label className="block mb-2">Preferred Tenure (years)</label>
              <Input required type="number" placeholder="Loan tenure" min="1" max="30" />
            </div>
            <Button type="submit" className="w-full">
              Submit Application
            </Button>
          </form>
        </CardContent>
      </Card>
    </div>
  );

  const renderConfirmationPage = () => (
    <div className="max-w-md mx-auto p-6 text-center space-y-8">
      <div className="flex flex-col items-center space-y-4">
        <CheckCircle className="h-16 w-16 text-green-500" />
        <h2 className="text-2xl font-bold">Thank you for booking with CredFill</h2>
        <p>Your Booking Number is #{bookingNumber}</p>
        <p className="text-gray-600">Use this number to track your loan application status</p>
      </div>
      <Button onClick={() => setCurrentPage('main')} variant="outline">
        Return to Home
      </Button>
    </div>
  );

  const pageComponents = {
    main: renderMainPage,
    loanType: renderLoanTypePage,
    booking: renderBookingPage,
    confirmation: renderConfirmationPage
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {pageComponents[currentPage]()}
    </div>
  );
};

export default CreditLoanPage;
