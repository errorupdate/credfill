console.log("booking.js");
let addDocumentButton = document.querySelector(".addDocumentButton");
console.log(addDocumentButton);
let documentList = document.querySelector(".documentList");
console.log(documentList);
addDocumentButton.addEventListener("click", function () {
  if (documentList.children.length >= 3) {
    return Swal.fire({
      title: "Error",
      text: "You can only upload a maximum of 3 documents",
      icon: "error",
      confirmButtonText: "Ok",
    });
  }
  let li = document.createElement("li");
  let input = document.createElement("input");
  let deleteButton = document.createElement("button");
  input.setAttribute("type", "file");
  input.setAttribute("name", "document");
  input.setAttribute("class", "input-field");
  deleteButton.innerHTML = `<ion-icon name="trash-outline"></ion-icon>`;
  deleteButton.setAttribute("class", "btn btn-primary deleteButton");
  deleteButton.setAttribute("type", "button");
  deleteButton.addEventListener("click", function () {
    li.remove();
  });
  li.appendChild(input);
  li.appendChild(deleteButton);
  documentList.prepend(li);

  // documentList.appendChild(documentItem);
});

document
  .getElementById("bookingForm")
  .addEventListener("submit", async (event) => {
    event.preventDefault();
    let formData = new FormData(event.target);
    let response = await fetch("https://sheetdb.io/api/v1/96gy32i8r3iom", {
      method: "POST",
      body: formData,
    });
    let result = await response.json();
    if (response.ok) {
      Swal.fire({
        title: "Success",
        text: "Booking has been created",
        icon: "success",
        confirmButtonText: "Ok",
      });
      event.target.reset();
    } else {
      Swal.fire({
        title: "Error",
        text: "Error while creating booking",
        icon: "error",
        confirmButtonText: "Ok",
      });
    }
  });

const Services = [
  {
    name: "Legal Compliance with MCA",
    price: null,
    path: "MCACompliance",
  },
  {
    name: "Audit of Financial Statements of Company",
    price: null,
    path: "AuditFS",
  },
  {
    name: "GST Registration",
    price: null,
    path: "GSTRegistration",
  },
  {
    name: "Strategic Management Advice for MSMEs",
    price: null,
    path: "StrategicManagementAdvice",
  },
  {
    name: "Trademark Registration",
    price: null,
    path: "TrademarkRegistration",
  },
  {
    name: "Income Tax Return Filing",
    price: null,
    path: "ITRFiling",
  },
  {
    name: "Company Registration",
    price: null,
    path: "CompanyRegistration",
  },
  {
    name : "ITR",
    price : null,
    path : "ITR"
  },
  {
    name: "ITR-1",
    price: null,
    path: "ITR-1",
  },
  {
    name: "ITR-2",
    price: null,
    path: "ITR-2",
  },
  {
    name: "ITR-3",
    price: null,
    path: "ITR-3",
  },
  {
    name: "ITR-4",
    price: null,
    path: "ITR-4",
  },
  {
    name: "Income Tax Notice",
    price: null,
    path: "ItdNotice",
  },
  {
    name: "Tax Audit(Individual)",
    price: null,
    path: "TaxAuditInd",
  },
  {
    name: "Tax Audit(Company)",
    price: null,
    path: "TaxAuditComp",
  },
  {
    name: "GST Return",
    price: null,
    path: "GSTReturn",
  },
  {
    name: "GST Department Notice Reply",
    price: null,
    path: "GSTNotice",
  },
  {
    name: "Accounting Services",
    price: null,
    path: "AccountingServices",
  },
  {
    name: "TDS Compliance & Consultancy",
    price: null,
    path: "TDSComp",
  },
  {
    name: "EPFO & ESIC",
    price: null,
    path: "EPFOESIC",
  },
  {
    name: "PTrademark and Brand Registration",
    price: null,
    path: "Tradeamark",
  },
  {
    name: "Patent Registration",
    price: null,
    path: "patent",
  },
  {
    name: "DPIIT Registration",
    price: null,
    path: "DPIITRegistration",
  },
  {
    name: "Project Report & CRM",
    price: null,
    path: "ProjectReport&CRM",
  },
  {
    name: "PITCH DECK & Investment Advisory",
    price: null,
    path: "InvestmentAdvisory",
  },
  {
    name: "Certificates",
    price: null,
    path: "CertificatesService",
  },
  {
    name: "Change In Directors of Company",
    price: null,
    path: "ChangeInDirectorofCompany",
  },
  {
    name: "Change in Share Capital Of Company",
    price: null,
    path: "ChangeInShareCapitalofCompany",
  },
  {
    name: "Certificates",
    price: null,
    path: "CertificatesService",
  },
  {
    name: "MCA Notice Reply",
    price: null,
    path: "MCANoticeReply",
  },
  {
    name: "MSME Registration",
    price: null,
    path: "MSMERegistration",
  },
  {
    name: "Shop & Establishment Registration",
    price: null,
    path: "Shop&EstablishmentRegistration",
  },
  {
    name: "ISO, FSSAI & Other Certification",
    price: null,
    path: "ISO&OtherCertification",
  },
  {
    name: "Import Export Code (IEC) & Export Assistance",
    price: null,
    path: "ExportAssistance",
  },
  {
    name: "Corporate Legal Advice",
    price: null,
    path: "CorporateLegalAdvice",
  },
  {
    name: "Strategic Management Advice for MSMEs",
    price: null,
    path: "StrategicManagementAdvice",
  },

  {
    name: "Other",
    price: null,
    path: "Other",
  },
];
window.onload = function () {
  let services = document.getElementById("service");
  Services.forEach((service) => {
    let option = document.createElement("option");
    option.value = service.name;
    option.innerHTML = service.name;
    services.appendChild(option);
  });

  // Auto Selecting the service from previous page URL
  let previousPageURL = document.referrer;
  let service = Services.find((service) =>
    previousPageURL.includes(service.path)
  );
  if (service) {
    services.value = service.name;
  }
};
