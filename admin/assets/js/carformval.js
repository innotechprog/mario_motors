const fileInput = document.getElementById("images");
const customBtn = document.getElementById("custom-images-btn");
const previewContainer = document.getElementById("preview-container");
const dropZone = document.getElementById("drop-zone");
let selectedFiles = []; // Final selected files
let pickerFiles = []; // All files loaded in picker
let pickerSelected = []; // Files selected in picker with order

// Car data for Make/Model/Variant dependencies
const carData = {
    "Audi": {
        "A1": ["1.0 TFSI", "1.4 TFSI", "S1"],
        "A3": ["1.0 TFSI", "1.4 TFSI", "2.0 TFSI", "2.0 TDI", "S3", "RS3"],
        "A4": ["1.4 TFSI", "2.0 TFSI", "2.0 TDI", "3.0 TDI", "S4", "RS4"],
        "A5": ["2.0 TFSI", "3.0 TFSI", "2.0 TDI", "3.0 TDI", "S5", "RS5"],
        "A6": ["2.0 TFSI", "3.0 TFSI", "2.0 TDI", "3.0 TDI", "S6", "RS6"],
        "A7": ["3.0 TFSI", "3.0 TDI", "S7", "RS7"],
        "A8": ["3.0 TFSI", "4.0 TFSI", "3.0 TDI", "S8"],
        "Q2": ["1.0 TFSI", "1.4 TFSI", "2.0 TDI"],
        "Q3": ["1.4 TFSI", "2.0 TFSI", "2.0 TDI", "SQ3", "RSQ3"],
        "Q5": ["2.0 TFSI", "3.0 TFSI", "2.0 TDI", "3.0 TDI", "SQ5"],
        "Q7": ["3.0 TFSI", "3.0 TDI", "4.0 TFSI", "SQ7"],
        "Q8": ["3.0 TFSI", "4.0 TFSI", "3.0 TDI", "SQ8", "RSQ8"],
        "TT": ["2.0 TFSI", "TTS", "TT RS"],
        "R8": ["5.2 V10", "5.2 V10 Plus"]
    },
    "BMW": {
        "1 Series": ["116i", "118i", "120i", "125i", "M135i"],
        "2 Series": ["218i", "220i", "230i", "M240i", "M2"],
        "3 Series": ["318i", "320i", "330i", "320d", "330d", "M340i", "M3"],
        "4 Series": ["420i", "430i", "440i", "420d", "430d", "M440i", "M4"],
        "5 Series": ["520i", "530i", "540i", "520d", "530d", "M550i", "M5"],
        "6 Series": ["630i", "640i", "650i", "M6"],
        "7 Series": ["730i", "740i", "750i", "760i", "M760i"],
        "8 Series": ["840i", "850i", "M850i", "M8"],
        "X1": ["sDrive18i", "xDrive20i", "xDrive25i", "sDrive18d", "xDrive20d"],
        "X2": ["sDrive18i", "xDrive20i", "xDrive25i", "M35i"],
        "X3": ["xDrive20i", "xDrive30i", "M40i", "xDrive20d", "xDrive30d", "X3 M"],
        "X4": ["xDrive20i", "xDrive30i", "M40i", "X4 M"],
        "X5": ["xDrive40i", "xDrive50i", "M50i", "xDrive30d", "xDrive40d", "X5 M"],
        "X6": ["xDrive40i", "xDrive50i", "M50i", "X6 M"],
        "X7": ["xDrive40i", "xDrive50i", "M50i"],
        "Z4": ["sDrive20i", "sDrive30i", "M40i"],
        "iX": ["xDrive40", "xDrive50", "M60"],
        "i4": ["eDrive40", "M50"],
        "i3": ["Electric", "REX"]
    },
    "Mercedes-Benz": {
        "A-Class": ["A160", "A180", "A200", "A220", "A250", "A35 AMG", "A45 AMG"],
        "B-Class": ["B180", "B200", "B220", "B250"],
        "C-Class": ["C160", "C180", "C200", "C300", "C220d", "C300d", "C43 AMG", "C63 AMG"],
        "E-Class": ["E200", "E250", "E300", "E400", "E450", "E220d", "E350d", "E53 AMG", "E63 AMG"],
        "S-Class": ["S350", "S400", "S450", "S500", "S560", "S63 AMG", "S65 AMG"],
        "CLA": ["CLA180", "CLA200", "CLA250", "CLA35 AMG", "CLA45 AMG"],
        "CLS": ["CLS350", "CLS450", "CLS53 AMG", "CLS63 AMG"],
        "GLA": ["GLA180", "GLA200", "GLA250", "GLA35 AMG", "GLA45 AMG"],
        "GLB": ["GLB200", "GLB250", "GLB35 AMG"],
        "GLC": ["GLC200", "GLC250", "GLC300", "GLC43 AMG", "GLC63 AMG"],
        "GLE": ["GLE300", "GLE350", "GLE400", "GLE450", "GLE53 AMG", "GLE63 AMG"],
        "GLS": ["GLS400", "GLS450", "GLS580", "GLS63 AMG"],
        "G-Class": ["G350", "G500", "G63 AMG"],
        "SL": ["SL400", "SL500", "SL63 AMG"],
        "AMG GT": ["GT", "GT S", "GT C", "GT R"]
    },
    "Toyota": {
        "Corolla": ["1.6 Valvematic", "1.8 Hybrid", "2.0 Hybrid", "GR Sport"],
        "Camry": ["2.5", "2.5 Hybrid", "3.5 V6"],
        "Avalon": ["3.5 V6", "Hybrid"],
        "Prius": ["1.8 Hybrid", "Prime"],
        "RAV4": ["2.0", "2.5", "2.5 Hybrid", "Prime"],
        "Highlander": ["3.5 V6", "2.5 Hybrid"],
        "4Runner": ["4.0 V6"],
        "Sequoia": ["5.7 V8"],
        "Land Cruiser": ["4.6 V8", "5.7 V8"],
        "Tacoma": ["2.7 4-Cyl", "3.5 V6"],
        "Tundra": ["5.7 V8", "Hybrid"],
        "Sienna": ["3.5 V6 Hybrid"],
        "C-HR": ["2.0", "1.8 Hybrid"],
        "Venza": ["2.5 Hybrid"],
        "Supra": ["2.0 Turbo", "3.0 Turbo"],
        "86": ["2.4 Boxer"]
    },
    "Honda": {
        "Civic": ["1.5 Turbo", "2.0", "2.0 Sport", "Type R", "Si"],
        "Accord": ["1.5 Turbo", "2.0 Turbo", "2.0 Hybrid"],
        "Insight": ["1.5 Hybrid"],
        "CR-V": ["1.5 Turbo", "2.0 Hybrid"],
        "Passport": ["3.5 V6"],
        "Pilot": ["3.5 V6"],
        "HR-V": ["1.8", "2.0"],
        "Odyssey": ["3.5 V6"],
        "Ridgeline": ["3.5 V6"],
        "Fit": ["1.5"],
        "CR-Z": ["1.5 Hybrid"]
    },
    "Ford": {
        "Fiesta": ["1.0 EcoBoost", "1.6", "ST"],
        "Focus": ["1.0 EcoBoost", "1.5 EcoBoost", "2.0 EcoBoost", "ST", "RS"],
        "Fusion": ["1.5 EcoBoost", "2.0 EcoBoost", "2.7 EcoBoost", "Hybrid"],
        "Mustang": ["2.3 EcoBoost", "5.0 GT", "Shelby GT350", "Shelby GT500"],
        "Escape": ["1.5 EcoBoost", "2.0 EcoBoost", "Hybrid"],
        "Edge": ["2.0 EcoBoost", "2.7 EcoBoost", "ST"],
        "Explorer": ["2.3 EcoBoost", "3.0 EcoBoost", "ST"],
        "Expedition": ["3.5 EcoBoost"],
        "F-150": ["3.3 V6", "2.7 EcoBoost", "3.5 EcoBoost", "5.0 V8", "Raptor", "Lightning"],
        "F-250": ["6.2 V8", "6.7 Diesel"],
        "F-350": ["6.2 V8", "6.7 Diesel"],
        "Ranger": ["2.3 EcoBoost"],
        "Bronco": ["2.3 EcoBoost", "2.7 EcoBoost"],
        "Bronco Sport": ["1.5 EcoBoost", "2.0 EcoBoost"]
    },
    "Chevrolet": {
        "Spark": ["1.4"],
        "Sonic": ["1.8"],
        "Cruze": ["1.4 Turbo", "1.6 Diesel"],
        "Malibu": ["1.5 Turbo", "2.0 Turbo"],
        "Impala": ["3.6 V6"],
        "Camaro": ["2.0 Turbo", "3.6 V6", "6.2 V8", "ZL1"],
        "Corvette": ["6.2 V8", "Z06", "ZR1"],
        "Trax": ["1.4 Turbo"],
        "Equinox": ["1.5 Turbo", "2.0 Turbo"],
        "Blazer": ["2.5", "3.6 V6"],
        "Traverse": ["3.6 V6"],
        "Tahoe": ["5.3 V8", "6.2 V8"],
        "Suburban": ["5.3 V8", "6.2 V8"],
        "Colorado": ["2.5", "3.6 V6", "2.8 Diesel"],
        "Silverado": ["4.3 V6", "5.3 V8", "6.2 V8", "3.0 Diesel"],
        "Bolt": ["Electric"]
    },
    "Nissan": {
        "Versa": ["1.6"],
        "Sentra": ["2.0"],
        "Altima": ["2.5", "2.0 Turbo", "AWD"],
        "Maxima": ["3.5 V6"],
        "370Z": ["3.7 V6"],
        "GT-R": ["3.8 Twin Turbo"],
        "Kicks": ["1.6"],
        "Rogue": ["2.5", "2.0 Turbo"],
        "Murano": ["3.5 V6"],
        "Pathfinder": ["3.5 V6"],
        "Armada": ["5.6 V8"],
        "Frontier": ["3.8 V6"],
        "Titan": ["5.6 V8"],
        "Leaf": ["Electric", "Plus"]
    },
    "Volkswagen": {
        "Golf": ["1.0 TSI", "1.4 TSI", "1.5 TSI", "2.0 TSI", "GTI", "Golf R"],
        "Polo": ["1.0 TSI", "1.4 TSI", "GTI"],
        "Passat": ["1.4 TSI", "2.0 TSI", "2.0 TDI", "2.0 BiTDI"],
        "Jetta": ["1.4 TSI", "2.0 TSI", "GLI"],
        "Tiguan": ["1.4 TSI", "2.0 TSI", "2.0 TDI"],
        "Touareg": ["3.0 TSI", "3.0 TDI", "4.0 TSI"],
        "Arteon": ["2.0 TSI", "2.0 TDI", "R-Line"],
        "T-Cross": ["1.0 TSI", "1.5 TSI"],
        "T-Roc": ["1.0 TSI", "1.5 TSI", "2.0 TSI", "R"],
        "Atlas": ["2.0 TSI", "3.6 VR6"],
        "ID.4": ["Pro", "Pro S", "AWD Pro"],
        "ID.3": ["Pro", "Pro S"],
        "e-Golf": ["Electric"],
        "Beetle": ["1.4 TSI", "2.0 TSI", "TDI"],
        "Scirocco": ["1.4 TSI", "2.0 TSI", "R"]
    },
    "Hyundai": {
        "Accent": ["1.6"],
        "Elantra": ["2.0", "1.6 Turbo", "N"],
        "Sonata": ["2.5", "1.6 Turbo", "Hybrid"],
        "Azera": ["3.3 V6"],
        "Veloster": ["2.0", "1.6 Turbo", "N"],
        "Genesis": ["3.8 V6", "5.0 V8"],
        "Venue": ["1.6"],
        "Kona": ["2.0", "1.6 Turbo", "Electric"],
        "Tucson": ["2.5", "1.6 Turbo", "Hybrid"],
        "Santa Fe": ["2.5", "2.5 Turbo", "Hybrid"],
        "Palisade": ["3.8 V6"],
        "Ioniq": ["Hybrid", "Plug-in", "Electric"],
        "Ioniq 5": ["Electric"],
        "Ioniq 6": ["Electric"]
    },
    "Kia": {
        "Rio": ["1.6"],
        "Forte": ["2.0", "1.6 Turbo"],
        "Optima": ["2.4", "1.6 Turbo", "2.0 Turbo", "Hybrid"],
        "Stinger": ["2.0 Turbo", "3.3 Twin Turbo"],
        "Soul": ["2.0", "1.6 Turbo", "Electric"],
        "Seltos": ["2.0", "1.6 Turbo"],
        "Sportage": ["2.4", "1.6 Turbo"],
        "Sorento": ["2.5", "1.6 Turbo", "Hybrid"],
        "Telluride": ["3.8 V6"],
        "Niro": ["Hybrid", "Plug-in", "Electric"],
        "EV6": ["Electric", "GT"]
    },
    "Mazda": {
        "Mazda2": ["1.5"],
        "Mazda3": ["2.0", "2.5", "2.5 Turbo"],
        "Mazda6": ["2.5", "2.5 Turbo"],
        "MX-5 Miata": ["2.0"],
        "CX-3": ["2.0"],
        "CX-30": ["2.5", "2.5 Turbo"],
        "CX-5": ["2.5", "2.5 Turbo"],
        "CX-9": ["2.5 Turbo"],
        "CX-50": ["2.5", "2.5 Turbo"]
    },
    "Subaru": {
        "Impreza": ["2.0", "2.0i"],
        "Legacy": ["2.5", "2.4 Turbo"],
        "Outback": ["2.5", "2.4 Turbo"],
        "Forester": ["2.5"],
        "Crosstrek": ["2.0", "2.5"],
        "Ascent": ["2.4 Turbo"],
        "WRX": ["2.4 Turbo", "STI"],
        "BRZ": ["2.4"]
    },
    "Mitsubishi": {
        "Mirage": ["1.2"],
        "Lancer": ["2.0", "2.4"],
        "Eclipse Cross": ["1.5 Turbo"],
        "Outlander": ["2.4", "3.0 V6"],
        "Outlander Sport": ["2.0", "2.4"],
        "Pajero": ["3.2 Diesel", "3.8 V6"]
    },
    "Lexus": {
        "IS": ["2.0 Turbo", "3.5 V6", "F Sport"],
        "ES": ["2.5", "3.5 V6", "Hybrid"],
        "GS": ["2.0 Turbo", "3.5 V6", "F Sport"],
        "LS": ["3.5 Twin Turbo", "Hybrid"],
        "LC": ["5.0 V8", "Hybrid"],
        "RC": ["2.0 Turbo", "3.5 V6", "F"],
        "UX": ["2.0", "Hybrid"],
        "NX": ["2.0 Turbo", "2.5 Hybrid"],
        "RX": ["3.5 V6", "Hybrid"],
        "GX": ["4.6 V8"],
        "LX": ["5.7 V8"]
    },
    "Infiniti": {
        "Q50": ["2.0 Turbo", "3.0 Twin Turbo", "Red Sport"],
        "Q60": ["2.0 Turbo", "3.0 Twin Turbo", "Red Sport"],
        "Q70": ["3.7 V6", "5.6 V8"],
        "QX30": ["2.0 Turbo"],
        "QX50": ["2.0 Turbo"],
        "QX60": ["3.5 V6"],
        "QX70": ["3.7 V6", "5.0 V8"],
        "QX80": ["5.6 V8"]
    },
    "Acura": {
        "ILX": ["2.4"],
        "TLX": ["2.4", "3.5 V6", "Type S"],
        "RLX": ["3.5 V6", "Hybrid"],
        "NSX": ["3.5 Twin Turbo Hybrid"],
        "RDX": ["2.0 Turbo"],
        "MDX": ["3.5 V6", "Type S"]
    },
    "Genesis": {
        "G70": ["2.0 Turbo", "3.3 Twin Turbo"],
        "G80": ["2.5 Turbo", "3.5 Twin Turbo"],
        "G90": ["3.3 Twin Turbo", "5.0 V8"],
        "GV60": ["Electric"],
        "GV70": ["2.5 Turbo", "3.5 Twin Turbo"],
        "GV80": ["2.5 Turbo", "3.5 Twin Turbo"]
    },
    "Cadillac": {
        "CT4": ["2.0 Turbo", "2.7 Turbo"],
        "CT5": ["2.0 Turbo", "3.0 Twin Turbo", "V"],
        "XT4": ["2.0 Turbo"],
        "XT5": ["2.0 Turbo", "3.6 V6"],
        "XT6": ["3.6 V6"],
        "Escalade": ["6.2 V8", "3.0 Diesel"]
    },
    "Lincoln": {
        "Corsair": ["2.0 Turbo", "2.3 Turbo"],
        "Nautilus": ["2.0 Turbo", "2.7 Twin Turbo"],
        "Aviator": ["3.0 Twin Turbo", "Hybrid"],
        "Navigator": ["3.5 Twin Turbo"]
    },
    "Volvo": {
        "S60": ["T5", "T6", "T8 Hybrid", "Polestar"],
        "S90": ["T5", "T6", "T8 Hybrid"],
        "V60": ["T5", "T6", "T8 Hybrid"],
        "V90": ["T5", "T6", "T8 Hybrid"],
        "XC40": ["T4", "T5", "Recharge"],
        "XC60": ["T5", "T6", "T8 Hybrid"],
        "XC90": ["T5", "T6", "T8 Hybrid"]
    },
    "Jaguar": {
        "XE": ["2.0 Turbo", "3.0 Supercharged"],
        "XF": ["2.0 Turbo", "3.0 Supercharged"],
        "XJ": ["3.0 Supercharged", "5.0 Supercharged"],
        "F-Type": ["2.0 Turbo", "3.0 Supercharged", "5.0 Supercharged", "R"],
        "E-Pace": ["2.0 Turbo"],
        "F-Pace": ["2.0 Turbo", "3.0 Supercharged", "SVR"],
        "I-Pace": ["Electric"]
    },
    "Land Rover": {
        "Range Rover Evoque": ["2.0 Turbo", "2.0 Diesel"],
        "Range Rover Velar": ["2.0 Turbo", "3.0 Supercharged"],
        "Range Rover Sport": ["3.0 Supercharged", "5.0 Supercharged", "SVR"],
        "Range Rover": ["3.0 Supercharged", "5.0 Supercharged", "SVAutobiography"],
        "Discovery Sport": ["2.0 Turbo", "2.0 Diesel"],
        "Discovery": ["3.0 Supercharged", "3.0 Diesel"],
        "Defender": ["2.0 Turbo", "3.0 Supercharged"]
    },
    "Porsche": {
        "718 Boxster": ["2.0 Turbo", "2.5 Turbo", "GTS", "Spyder"],
        "718 Cayman": ["2.0 Turbo", "2.5 Turbo", "GTS", "GT4"],
        "911": ["Carrera", "Carrera S", "Turbo", "Turbo S", "GT3", "GT3 RS"],
        "Panamera": ["4", "4S", "GTS", "Turbo", "Turbo S"],
        "Macan": ["Base", "S", "GTS", "Turbo"],
        "Cayenne": ["Base", "S", "GTS", "Turbo", "Turbo S"],
        "Taycan": ["4S", "Turbo", "Turbo S"]
    },
    "Maserati": {
        "Ghibli": ["Base", "S", "Trofeo"],
        "Quattroporte": ["S", "GTS", "Trofeo"],
        "Levante": ["Base", "S", "GTS", "Trofeo"],
        "MC20": ["V6 Twin Turbo"]
    },
    "Ferrari": {
        "Portofino": ["3.9 Twin Turbo"],
        "Roma": ["3.9 Twin Turbo"],
        "F8 Tributo": ["3.9 Twin Turbo"],
        "SF90 Stradale": ["4.0 Hybrid"],
        "812 Superfast": ["6.5 V12"],
        "GTC4Lusso": ["6.3 V12"]
    },
    "Lamborghini": {
        "Huracán": ["5.2 V10", "Performante", "STO"],
        "Aventador": ["6.5 V12", "S", "SVJ"],
        "Urus": ["4.0 Twin Turbo"]
    },
    "McLaren": {
        "570S": ["3.8 Twin Turbo"],
        "720S": ["4.0 Twin Turbo"],
        "GT": ["4.0 Twin Turbo"],
        "Artura": ["3.0 Hybrid"]
    },
    "Bentley": {
        "Continental GT": ["6.0 W12", "4.0 V8"],
        "Flying Spur": ["6.0 W12", "4.0 V8"],
        "Bentayga": ["6.0 W12", "4.0 V8"]
    },
    "Rolls-Royce": {
        "Ghost": ["6.75 V12"],
        "Phantom": ["6.75 V12"],
        "Wraith": ["6.6 V12"],
        "Dawn": ["6.6 V12"],
        "Cullinan": ["6.75 V12"]
    },
    "Tesla": {
        "Model 3": ["Standard Range", "Long Range", "Performance"],
        "Model S": ["Long Range", "Plaid"],
        "Model X": ["Long Range", "Plaid"],
        "Model Y": ["Long Range", "Performance"]
    },
    "Peugeot": {
        "208": ["1.2 PureTech", "1.5 BlueHDi", "e-208"],
        "308": ["1.2 PureTech", "1.5 BlueHDi", "1.6 THP"],
        "508": ["1.6 PureTech", "2.0 BlueHDi", "PSE"],
        "2008": ["1.2 PureTech", "1.5 BlueHDi", "e-2008"],
        "3008": ["1.2 PureTech", "1.6 THP", "1.5 BlueHDi"],
        "5008": ["1.2 PureTech", "1.6 THP", "2.0 BlueHDi"]
    },
    "Citroën": {
        "C1": ["1.0 VTi", "1.2 PureTech"],
        "C3": ["1.2 PureTech", "1.5 BlueHDi"],
        "C4": ["1.2 PureTech", "1.5 BlueHDi", "e-C4"],
        "C5 Aircross": ["1.2 PureTech", "1.6 THP", "1.5 BlueHDi"],
        "Berlingo": ["1.2 PureTech", "1.5 BlueHDi"]
    },
    "Renault": {
        "Clio": ["1.0 TCe", "1.3 TCe", "1.5 dCi"],
        "Megane": ["1.3 TCe", "1.6 TCe", "1.5 dCi", "RS"],
        "Kadjar": ["1.3 TCe", "1.5 dCi"],
        "Captur": ["1.0 TCe", "1.3 TCe", "1.5 dCi"],
        "Koleos": ["2.5", "1.7 dCi"],
        "Zoe": ["Electric"]
    },
    "Skoda": {
        "Fabia": ["1.0 MPI", "1.0 TSI"],
        "Octavia": ["1.0 TSI", "1.4 TSI", "2.0 TSI", "2.0 TDI", "RS"],
        "Superb": ["1.4 TSI", "2.0 TSI", "2.0 TDI"],
        "Kamiq": ["1.0 TSI", "1.5 TSI"],
        "Karoq": ["1.0 TSI", "1.5 TSI", "2.0 TDI"],
        "Kodiaq": ["1.4 TSI", "2.0 TSI", "2.0 TDI", "RS"]
    },
    "SEAT": {
        "Ibiza": ["1.0 MPI", "1.0 TSI", "1.5 TSI"],
        "Leon": ["1.0 TSI", "1.4 TSI", "2.0 TSI", "2.0 TDI", "Cupra"],
        "Arona": ["1.0 TSI", "1.5 TSI"],
        "Ateca": ["1.0 TSI", "1.4 TSI", "2.0 TSI", "2.0 TDI"],
        "Tarraco": ["1.4 TSI", "2.0 TSI", "2.0 TDI"]
    },
    "Alfa Romeo": {
        "Giulietta": ["1.4 TB", "1.6 JTDM", "1.9 JTDM"],
        "Giulia": ["2.0 Turbo", "2.2 Diesel", "Quadrifoglio"],
        "Stelvio": ["2.0 Turbo", "2.2 Diesel", "Quadrifoglio"],
        "4C": ["1.75 TBi"]
    },
    "Fiat": {
        "500": ["1.2", "0.9 TwinAir", "Abarth"],
        "Panda": ["1.2", "0.9 TwinAir"],
        "Tipo": ["1.4", "1.6", "1.3 MultiJet"],
        "500X": ["1.4 TB", "1.6", "1.3 MultiJet"],
        "500L": ["1.4", "0.9 TwinAir", "1.3 MultiJet"]
    },
    "Jeep": {
        "Renegade": ["1.4 TB", "2.4", "1.3 TB"],
        "Compass": ["1.4 TB", "2.4", "1.3 TB"],
        "Cherokee": ["2.4", "3.2 V6"],
        "Grand Cherokee": ["3.6 V6", "5.7 V8", "6.4 V8", "Trackhawk"],
        "Wrangler": ["3.6 V6", "2.0 Turbo", "392 V8"],
        "Gladiator": ["3.6 V6"]
    },
    "Dodge": {
        "Charger": ["3.6 V6", "5.7 V8", "6.4 V8", "Hellcat"],
        "Challenger": ["3.6 V6", "5.7 V8", "6.4 V8", "Hellcat", "Demon"],
        "Durango": ["3.6 V6", "5.7 V8", "6.4 V8", "Hellcat"]
    },
    "Ram": {
        "1500": ["3.6 V6", "5.7 V8", "3.0 Diesel", "TRX"],
        "2500": ["6.4 V8", "6.7 Diesel"],
        "3500": ["6.4 V8", "6.7 Diesel"]
    },
    "Chrysler": {
        "300": ["3.6 V6", "5.7 V8"],
        "Pacifica": ["3.6 V6", "Hybrid"]
    }
};

// Initialize dropdown dependencies
document.addEventListener('DOMContentLoaded', function() {
    const makeSelect = document.getElementById('make');
    const modelSelect = document.getElementById('model');
    const variantSelect = document.getElementById('variant');
    const customMakeContainer = document.getElementById('custom-make-container');
    const customModelContainer = document.getElementById('custom-model-container');
    const customVariantContainer = document.getElementById('custom-variant-container');

    // Make change handler
    makeSelect.addEventListener('change', function() {
        const selectedMake = this.value;
        
        // Handle custom make field
        if (selectedMake === 'Other') {
            customMakeContainer.style.display = 'block';
            document.getElementById('custom_make').required = true;
        } else {
            customMakeContainer.style.display = 'none';
            document.getElementById('custom_make').required = false;
            document.getElementById('custom_make').value = '';
        }
        
        // Reset model and variant
        modelSelect.innerHTML = '<option value="">Select Model</option>';
        variantSelect.innerHTML = '<option value="">Select Model First</option>';
        variantSelect.disabled = true;
        customModelContainer.style.display = 'none';
        customVariantContainer.style.display = 'none';
        document.getElementById('custom_model').required = false;
        document.getElementById('custom_variant_input').required = false;
        
        if (selectedMake && selectedMake !== 'Other' && carData[selectedMake]) {
            modelSelect.disabled = false;
            
            // Populate models for selected make
            Object.keys(carData[selectedMake]).forEach(model => {
                const option = document.createElement('option');
                option.value = model;
                option.textContent = model;
                modelSelect.appendChild(option);
            });
            
            // Always add "Other" option to model for manual input
            const otherOption = document.createElement('option');
            otherOption.value = 'Other';
            otherOption.textContent = 'Other (Manual Input)';
            modelSelect.appendChild(otherOption);
        } else if (selectedMake === 'Other') {
            // If make is "Other", enable model but show custom field
            modelSelect.disabled = true;
            customModelContainer.style.display = 'block';
            document.getElementById('custom_model').required = true;
        } else if (selectedMake && selectedMake !== 'Other') {
            // For makes not in our data, just show "Other" option
            modelSelect.disabled = false;
            const otherOption = document.createElement('option');
            otherOption.value = 'Other';
            otherOption.textContent = 'Other (Manual Input)';
            modelSelect.appendChild(otherOption);
        } else {
            modelSelect.disabled = true;
        }
    });

    // Model change handler
    modelSelect.addEventListener('change', function() {
        const selectedMake = makeSelect.value;
        const selectedModel = this.value;
        
        // Handle custom model field
        if (selectedModel === 'Other') {
            customModelContainer.style.display = 'block';
            document.getElementById('custom_model').required = true;
        } else {
            customModelContainer.style.display = 'none';
            document.getElementById('custom_model').required = false;
            document.getElementById('custom_model').value = '';
        }
        
        // Reset variant
        variantSelect.innerHTML = '<option value="">Select Variant</option>';
        customVariantContainer.style.display = 'none';
        document.getElementById('custom_variant_input').required = false;
        
        if (selectedMake && selectedModel && selectedModel !== 'Other' && carData[selectedMake] && carData[selectedMake][selectedModel]) {
            variantSelect.disabled = false;
            
            // Populate variants for selected model
            carData[selectedMake][selectedModel].forEach(variant => {
                const option = document.createElement('option');
                option.value = variant;
                option.textContent = variant;
                variantSelect.appendChild(option);
            });
            
            // Always add "Other" option to variant for manual input
            const otherOption = document.createElement('option');
            otherOption.value = 'Other';
            otherOption.textContent = 'Other (Manual Input)';
            variantSelect.appendChild(otherOption);
        } else if (selectedModel === 'Other' || selectedMake === 'Other') {
            // If model is "Other" or make is "Other", enable variant but show custom field
            variantSelect.disabled = true;
            customVariantContainer.style.display = 'block';
            document.getElementById('custom_variant_input').required = true;
        } else if (selectedModel && selectedModel !== 'Other') {
            // For models not in our data or when no variants defined, just show "Other" option
            variantSelect.disabled = false;
            const otherOption = document.createElement('option');
            otherOption.value = 'Other';
            otherOption.textContent = 'Other (Manual Input)';
            variantSelect.appendChild(otherOption);
        } else {
            variantSelect.disabled = true;
        }
    });

    // Variant change handler
    variantSelect.addEventListener('change', function() {
        const selectedVariant = this.value;
        
        // Handle custom variant field
        if (selectedVariant === 'Other') {
            customVariantContainer.style.display = 'block';
            document.getElementById('custom_variant_input').required = true;
        } else {
            customVariantContainer.style.display = 'none';
            document.getElementById('custom_variant_input').required = false;
            document.getElementById('custom_variant_input').value = '';
        }
    });
});

// Open custom image picker modal
customBtn.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    
    // Show loading state
    customBtn.disabled = true;
    customBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
    
    // Trigger file input
    fileInput.click();
});

// Also allow clicking anywhere on the drop zone
dropZone.addEventListener("click", (e) => {
    if (e.target !== customBtn && !customBtn.contains(e.target)) {
        customBtn.click();
    }
});

// Drag and Drop Events
dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.style.borderColor = "#0d6efd";
    dropZone.style.background = "#e7f1ff";
});

dropZone.addEventListener("dragleave", (e) => {
    e.preventDefault();
    dropZone.style.borderColor = "#ccc";
    dropZone.style.background = "#f8f9fa";
});

dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.style.borderColor = "#ccc";
    dropZone.style.background = "#f8f9fa";
    
    const files = Array.from(e.dataTransfer.files);
    const imageFiles = files.filter(file => file.type.startsWith('image/'));
    
    if (imageFiles.length > 0) {
        addFilesDirectly(imageFiles);
    } else {
        alert("Please drop only image files");
    }
});

// Mobile detection function
function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
           (window.innerWidth <= 768 && window.ontouchstart !== undefined);
}

// Get appropriate button text based on device type
function getSelectImagesButtonText() {
    if (isMobileDevice()) {
        return '<i class="bi bi-images"></i> Select Images (Order Matters)';
    } else {
        return '<i class="bi bi-images"></i> Select Images in Order';
    }
}

// Initialize button text on page load
document.addEventListener('DOMContentLoaded', function() {
    if (customBtn) {
        customBtn.innerHTML = getSelectImagesButtonText();
    }
});

// Handle file selection - opens picker modal for desktop, direct add for mobile
fileInput.addEventListener("change", (event) => {
    // Reset button state
    customBtn.disabled = false;
    customBtn.innerHTML = getSelectImagesButtonText();
    
    const files = Array.from(event.target.files);
    if (files.length > 0) {
        if (isMobileDevice()) {
            // For mobile devices, add files directly in selection order
            console.log('📱 Mobile device detected - adding files directly in order');
            addFilesDirectly(files);
        } else {
            // For desktop, use the picker modal for advanced selection
            console.log('🖥️ Desktop device - opening picker modal');
            showPickerModal(files);
        }
    }
});

// Reset button if user cancels file dialog
window.addEventListener('focus', () => {
    setTimeout(() => {
        if (customBtn.disabled && fileInput.files.length === 0) {
            customBtn.disabled = false;
            customBtn.innerHTML = getSelectImagesButtonText();
        }
    }, 300);
});

// Show the picker modal with loaded images
function showPickerModal(files) {
    console.log('📂 Opening picker with', files.length, 'files');
    
    try {
        pickerFiles = files;
        pickerSelected = [...selectedFiles]; // Keep existing selections
        
        const modalElement = document.getElementById('imagePickerModal');
        if (!modalElement) {
            console.error('❌ Picker modal not found!');
            addFilesDirectly(files);
            return;
        }
        
        console.log('✓ Modal element found');
        
        // Show modal first
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        console.log('✓ Modal opened');
        
        // Show loading message
        const grid = document.getElementById('picker-grid');
        grid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">Loading ${files.length} images...</p>
            </div>
        `;
        
        // Render grid after a short delay
        setTimeout(() => {
            console.log('🖼️ Starting to render grid...');
            renderPickerGrid();
            setTimeout(() => {
                updatePickerVisualState();
            }, pickerFiles.length * 10 + 100);
            updatePickerPreview();
        }, 100);
        
    } catch (error) {
        console.error('❌ Error showing picker modal:', error);
        addFilesDirectly(files);
    }
}

// Render the grid of all images in picker (with progressive loading)
function renderPickerGrid() {
    console.log('📋 Rendering grid for', pickerFiles.length, 'files');
    
    const grid = document.getElementById('picker-grid');
    grid.innerHTML = '';
    
    // Create placeholders first for all images
    const placeholders = [];
    pickerFiles.forEach((file, index) => {
        const col = document.createElement('div');
        col.className = 'col-6 col-sm-4 col-md-3 col-lg-2';
        col.innerHTML = `
            <div class="card h-100">
                <div class="d-flex align-items-center justify-content-center" style="height: 120px; background: #f0f0f0;">
                    <div class="spinner-border spinner-border-sm text-primary"></div>
                </div>
                <div class="card-body p-1">
                    <small class="text-muted" style="font-size: 0.7rem;">Loading...</small>
                </div>
            </div>
        `;
        grid.appendChild(col);
        placeholders[index] = col;
    });
    
    console.log('✓ Created', placeholders.length, 'placeholders');
    
    // Load images progressively
    let loadedCount = 0;
    pickerFiles.forEach((file, index) => {
        // Add small delay between loading images to prevent UI freeze
        setTimeout(() => {
            const reader = new FileReader();
            reader.onload = (e) => {
                // Check if this file is already selected
                const selectedIndex = pickerSelected.findIndex(
                    f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
                );
                const isSelected = selectedIndex !== -1;
                const orderNumber = isSelected ? selectedIndex + 1 : '';
                
                placeholders[index].innerHTML = `
                    <div class="card picker-card h-100 ${isSelected ? 'selected' : ''}" 
                         data-index="${index}" 
                         data-order="${orderNumber}"
                         style="cursor: pointer;">
                        <div class="position-relative">
                            <img src="${e.target.result}" class="card-img-top">
                            <div class="picker-overlay"></div>
                        </div>
                        <div class="card-body p-1">
                            <small class="text-truncate d-block" style="font-size: 0.7rem;" title="${file.name}">${file.name}</small>
                        </div>
                    </div>
                `;
                
                // Add click handler
                const card = placeholders[index].querySelector('.picker-card');
                if (card) {
                    card.addEventListener('click', () => {
                        console.log('🖱️ Clicked card index:', index, 'File:', file.name);
                        toggleImageSelection(index);
                    });
                    loadedCount++;
                    if (loadedCount === pickerFiles.length) {
                        console.log('✅ All', loadedCount, 'images loaded and click handlers attached');
                    }
                } else {
                    console.error('❌ Card not found after rendering for index:', index);
                }
            };
            reader.readAsDataURL(file);
        }, index * 10); // Stagger by 10ms each
    });
}

// Toggle image selection in picker
function toggleImageSelection(index) {
    console.log('⚡ Toggle called for index:', index);
    
    const file = pickerFiles[index];
    if (!file) {
        console.error('❌ No file at index:', index);
        return;
    }
    
    console.log('📄 File:', file.name);
    
    // Get the specific card that was clicked
    const grid = document.getElementById('picker-grid');
    const cards = grid.querySelectorAll('.picker-card');
    const clickedCard = cards[index];
    
    console.log('🎴 Found', cards.length, 'cards, clicked card:', clickedCard);
    
    if (!clickedCard) {
        console.error('❌ No card at index:', index);
        return;
    }
    
    // Check if already selected
    const selectedIndex = pickerSelected.findIndex(
        f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
    );
    
    if (selectedIndex !== -1) {
        // Deselect - remove from array
        pickerSelected.splice(selectedIndex, 1);
        console.log('❌ Deselected, now have:', pickerSelected.length, 'selected');
        
        // Immediately update ONLY this card
        clickedCard.classList.remove('selected');
        clickedCard.setAttribute('data-order', '');
        console.log('✓ Updated card - removed selection');
    } else {
        // Select - add to array
        pickerSelected.push(file);
        console.log('✅ Selected, now have:', pickerSelected.length, 'selected');
        
        // Immediately update ONLY this card
        clickedCard.classList.add('selected');
        clickedCard.setAttribute('data-order', pickerSelected.length);
        console.log('✓ Updated card - added selection as #', pickerSelected.length);
    }
    
    // Batch update others that need number changes (debounced)
    requestAnimationFrame(() => {
        console.log('🔄 Updating other card numbers...');
        updateOtherCardsNumbers();
        updatePickerPreview();
    });
}

// Update only the cards whose numbers changed (not all cards)
function updateOtherCardsNumbers() {
    const grid = document.getElementById('picker-grid');
    const cards = grid.querySelectorAll('.picker-card.selected');
    
    // Only update selected cards to fix their order numbers
    cards.forEach((card) => {
        const cardIndex = parseInt(card.getAttribute('data-index'));
        const file = pickerFiles[cardIndex];
        if (!file) return;
        
        const selectedIndex = pickerSelected.findIndex(
            f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
        );
        
        if (selectedIndex !== -1) {
            const orderNumber = selectedIndex + 1;
            // Only update if number changed
            if (card.getAttribute('data-order') !== String(orderNumber)) {
                card.setAttribute('data-order', orderNumber);
            }
        }
    });
}

// Full visual state update (used only on modal open)
function updatePickerVisualState() {
    const grid = document.getElementById('picker-grid');
    const cards = grid.querySelectorAll('.picker-card');
    
    cards.forEach((card, cardIndex) => {
        const file = pickerFiles[cardIndex];
        if (!file) return;
        
        const selectedIndex = pickerSelected.findIndex(
            f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
        );
        
        const isSelected = selectedIndex !== -1;
        const orderNumber = isSelected ? selectedIndex + 1 : 0;
        
        if (isSelected) {
            card.classList.add('selected');
            card.setAttribute('data-order', orderNumber);
        } else {
            card.classList.remove('selected');
            card.setAttribute('data-order', '');
        }
    });
}

// Update the preview strip at bottom of picker
function updatePickerPreview() {
    const preview = document.getElementById('picker-preview');
    const counter = document.getElementById('picker-counter');
    
    counter.textContent = `${pickerSelected.length} selected`;
    
    if (pickerSelected.length === 0) {
        preview.innerHTML = '<div class="text-muted small">No images selected</div>';
        return;
    }
    
    preview.innerHTML = '';
    
    pickerSelected.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const thumb = document.createElement('div');
            thumb.className = 'position-relative';
            thumb.style.minWidth = '60px';
            
            // Special styling for primary image (first one)
            const isPrimary = index === 0;
            const borderColor = isPrimary ? '#dc3545' : '#0d6efd';
            const badgeColor = isPrimary ? 'bg-danger' : 'bg-primary';
            const badgeText = isPrimary ? '★1' : `#${index + 1}`;
            
            thumb.innerHTML = `
                <img src="${e.target.result}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; border: 2px solid ${borderColor};">
                <span class="badge ${badgeColor} position-absolute" style="top: -5px; left: -5px; font-size: 0.7rem;">${badgeText}</span>
                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: -5px; right: -5px; width: 20px; height: 20px; padding: 0; font-size: 0.7rem; line-height: 1;" onclick="window.removeFromPicker(${index})">
                    <i class="bi bi-x"></i>
                </button>
                ${isPrimary ? '<div class="text-center mt-1"><small class="text-danger fw-bold">PRIMARY</small></div>' : ''}
            `;
            preview.appendChild(thumb);
        };
        reader.readAsDataURL(file);
    });
}

// Remove image from picker selection
window.removeFromPicker = function(index) {
    pickerSelected.splice(index, 1);
    renderPickerGrid();
    updatePickerPreview();
}

// Clear all picker selections
window.clearPickerSelection = function() {
    if (confirm('Remove all selected images?')) {
        pickerSelected = [];
        renderPickerGrid();
        updatePickerPreview();
    }
}

// Confirm selection and close picker
window.confirmImageSelection = function() {
    selectedFiles = [...pickerSelected];
    
    // Hide drop zone if images selected
    if (selectedFiles.length > 0) {
        dropZone.style.display = 'none';
    }
    
    displayImages();
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('imagePickerModal'));
    modal.hide();
    
    // Reset file input
    fileInput.value = '';
}

// Add files directly (for drag & drop)
function addFilesDirectly(files) {
    files.forEach(file => {
        const isDuplicate = selectedFiles.some(
            f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
        );
        if (!isDuplicate) {
            selectedFiles.push(file);
        }
    });

    if (selectedFiles.length > 0) {
        dropZone.style.display = 'none';
    }
    
    displayImages();
}

// Display selected images in main preview
function displayImages() {
    previewContainer.innerHTML = "";

    if (selectedFiles.length > 0) {
        // Add header with controls
        const headerDiv = document.createElement("div");
        headerDiv.classList.add("mb-3");
        const primaryText = selectedFiles.length > 0 ? ` | <span class="text-danger fw-bold">First image is PRIMARY</span>` : '';
        headerDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                <div>
                    <i class="bi bi-images text-primary"></i>
                    <strong class="ms-2">${selectedFiles.length} image${selectedFiles.length > 1 ? 's' : ''} selected in order${primaryText}</strong>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.sortImagesByName()" title="Sort alphabetically (will change order!)">
                        <i class="bi bi-sort-alpha-down"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.addMoreImages()" title="Add more images">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="window.clearAllImages()" title="Remove all">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        previewContainer.appendChild(headerDiv);

        // Create grid container with drag and drop functionality
        const gridContainer = document.createElement("div");
        gridContainer.classList.add("row", "g-2");
        gridContainer.id = "image-grid-container";
        previewContainer.appendChild(gridContainer);

        // Add drag and drop instructions (only for desktop)
        if (!isMobileDevice()) {
            const instructionsDiv = document.createElement("div");
            instructionsDiv.classList.add("alert", "alert-light", "small", "mb-3");
            instructionsDiv.innerHTML = `
                <i class="bi bi-hand-index"></i> 
                <strong>Tip:</strong> You can drag and drop images below to reorder them. The first image will always be the primary/main image.
            `;
            previewContainer.insertBefore(instructionsDiv, gridContainer);
        } else {
            // Mobile-friendly message
            const instructionsDiv = document.createElement("div");
            instructionsDiv.classList.add("alert", "alert-info", "small", "mb-3");
            instructionsDiv.innerHTML = `
                <i class="bi bi-phone"></i> 
                <strong>Mobile Mode:</strong> Images are automatically numbered in the order you selected them. The first image is your primary image.
            `;
            previewContainer.insertBefore(instructionsDiv, gridContainer);
        }

        // Create placeholder divs for each image to maintain order
        const placeholders = [];
        selectedFiles.forEach((file, index) => {
            const colDiv = document.createElement("div");
            colDiv.classList.add("col-6", "col-sm-4", "col-md-3");
            
            // Only enable drag and drop on desktop devices
            if (!isMobileDevice()) {
                colDiv.draggable = true;
                colDiv.dataset.index = index;
                
                // Add drag event listeners
                colDiv.addEventListener('dragstart', handleDragStart);
                colDiv.addEventListener('dragover', handleDragOver);
                colDiv.addEventListener('dragleave', handleDragLeave);
                colDiv.addEventListener('drop', handleDrop);
                colDiv.addEventListener('dragend', handleDragEnd);
            } else {
                // For mobile, disable dragging but keep the structure
                colDiv.draggable = false;
                colDiv.dataset.index = index;
            }
            
            colDiv.innerHTML = `
                <div class="card h-100 shadow-sm">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="height: 150px; background: #f0f0f0;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="position-absolute top-0 end-0 p-2">
                            <i class="bi bi-grip-vertical text-muted grip-handle" title="Drag to reorder"></i>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <p class="text-muted small">Loading...</p>
                    </div>
                </div>
            `;
            gridContainer.appendChild(colDiv);
            placeholders[index] = colDiv;
        });

        // Load images and replace placeholders in order
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
                const fileSize = file.size > 1024 * 1024 
                    ? (file.size / (1024 * 1024)).toFixed(2) + ' MB'
                    : (file.size / 1024).toFixed(2) + ' KB';

                // Special styling for primary image (first one)
                const isPrimary = index === 0;
                const borderColor = isPrimary ? 'border-danger' : '';
                const badgeColor = isPrimary ? 'bg-danger' : 'bg-primary';
                const badgeText = isPrimary ? '★1 PRIMARY' : `#${index + 1}`;

                placeholders[index].innerHTML = `
                    <div class="card h-100 shadow-sm ${borderColor}">
                        <div class="position-relative" onclick="window.showImagePreview(${index})">
                            <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover; cursor: pointer;">
                            <span class="badge ${badgeColor} position-absolute" style="top: 5px; left: 5px;">${badgeText}</span>
                            <div class="position-absolute top-0 end-0 p-2">
                                <i class="bi bi-grip-vertical text-white bg-dark rounded px-1 grip-handle" title="Drag to reorder"></i>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <p class="card-text small text-truncate mb-1" title="${file.name}">
                                <i class="bi bi-file-image"></i> ${file.name}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">${fileSize}</small>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.removeImage(${index})" title="Remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            ${isPrimary ? '<div class="text-center mt-1"><small class="text-danger fw-bold">This will be the main image</small></div>' : ''}
                        </div>
                    </div>
                `;
        };
        reader.readAsDataURL(file);
    });
    } else {
        dropZone.style.display = 'block';
    }
}

// Show image in full-screen modal
window.showImagePreview = function(index) {
    try {
        const file = selectedFiles[index];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const modalElement = document.getElementById('imagePreviewModal');
                const modalImageName = document.getElementById('modalImageName');
                const modalImagePreview = document.getElementById('modalImagePreview');
                
                if (!modalElement || !modalImageName || !modalImagePreview) {
                    window.open(e.target.result, '_blank');
                    return;
                }
                
                modalImageName.textContent = file.name;
                modalImagePreview.src = e.target.result;
                
                if (typeof bootstrap !== 'undefined') {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    window.open(e.target.result, '_blank');
                }
            } catch (error) {
                console.error('Error showing preview modal:', error);
                window.open(e.target.result, '_blank');
            }
        };
        reader.readAsDataURL(file);
    } catch (error) {
        console.error('Error in showImagePreview:', error);
    }
}

// Remove an image
window.removeImage = function(index) {
    selectedFiles.splice(index, 1);
    displayImages();
}

// Clear all images
window.clearAllImages = function() {
    if (confirm('Remove all selected images?')) {
        selectedFiles = [];
        fileInput.value = '';
        displayImages();
    }
}

// Add more images
window.addMoreImages = function() {
    fileInput.click();
}

// Sort by name
window.sortImagesByName = function() {
    if (confirm('Warning: Sorting by name will change the order you carefully selected in the image picker. The first image will no longer be your chosen primary image. Are you sure you want to continue?')) {
        selectedFiles.sort((a, b) => a.name.localeCompare(b.name));
        displayImages();
    }
}

// Form submission
document.getElementById("carForm").addEventListener("submit", function (e) {
    e.preventDefault();

    if (!validateForm()) {
        return false;
    }

    if (selectedFiles.length > 0) {
        // Create a progress indicator for image compression
        const progress = document.getElementById('progress');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        
        progress.style.display = 'block';
        progressBar.style.width = '0%';
        progressText.textContent = 'Preparing images...';
        
        const compressedFiles = new Array(selectedFiles.length);
        let processedCount = 0;

        selectedFiles.forEach((file, index) => {
            compressImage(file, 1280, 960, 0.7, (compressedFile) => {
                compressedFiles[index] = compressedFile;
                processedCount++;
                
                // Update compression progress
                const compressionProgress = (processedCount / selectedFiles.length) * 30; // 30% for compression
                progressBar.style.width = compressionProgress + '%';
                progressText.textContent = `Preparing images... ${processedCount}/${selectedFiles.length}`;

                if (processedCount === selectedFiles.length) {
                    submitForm(compressedFiles);
                }
            });
        });
    } else {
        submitForm([]);
    }
});

// Validate form
function validateForm() {
    let isValid = true;
    let firstErrorElement = null;

    document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

    function setError(field, message) {
        const errorElement = document.getElementById(field + '-error');
        if (errorElement) {
            errorElement.textContent = message;
            if (!firstErrorElement) firstErrorElement = errorElement;
        }
        isValid = false;
    }

    console.log('Starting form validation...'); // Debug log

    if (!document.querySelector('select[name="year"]').value) setError('year', 'Please select a year.');
    
    // Make validation
    const makeValue = document.querySelector('select[name="make"]').value;
    const customMakeValue = document.querySelector('input[name="custom_make"]').value;
    console.log('Make value:', makeValue, 'Custom make:', customMakeValue); // Debug log
    
    if (!makeValue) {
        setError('make', 'Please select the car make.');
    } else if (makeValue === 'Other' && !customMakeValue.trim()) {
        setError('custom-make', 'Please specify the custom make.');
    }
    
    // Model validation
    const modelValue = document.querySelector('select[name="model"]').value;
    const customModelValue = document.querySelector('input[name="custom_model"]').value;
    console.log('Model value:', modelValue, 'Custom model:', customModelValue); // Debug log
    
    if (makeValue && makeValue !== 'Other') {
        // If make is selected and not "Other", model is required
        if (!modelValue) {
            setError('model', 'Please select the car model or choose "Other" for manual input.');
        } else if (modelValue === 'Other' && !customModelValue.trim()) {
            setError('custom-model', 'Please specify the custom model.');
        }
    } else if (makeValue === 'Other' && !customModelValue.trim()) {
        // If make is "Other", custom model is required
        setError('custom-model', 'Please specify the custom model.');
    }
    
    // Variant validation (optional, but if Other is selected, custom variant is required)
    const variantValue = document.querySelector('select[name="variant"]').value;
    const customVariantValue = document.querySelector('input[name="custom_variant_input"]').value;
    console.log('Variant value:', variantValue, 'Custom variant:', customVariantValue); // Debug log
    
    if (variantValue === 'Other' && !customVariantValue.trim()) {
        setError('custom-variant-input', 'Please specify the custom variant.');
    }
    
    if (!document.querySelector('input[name="mileage"]').value || document.querySelector('input[name="mileage"]').value < 0) setError('mileage', 'Please enter a valid mileage.');
    if (!document.querySelector('input[name="price"]').value || document.querySelector('input[name="price"]').value < 0) setError('price', 'Please enter a valid price.');
    if (!document.querySelector('select[name="color"]').value) setError('color', 'Please select the car color.');
    if (!document.querySelector('select[name="transmission"]').value) setError('transmission', 'Please select the transmission type.');
    if (!document.querySelector('select[name="fuel_type"]').value) setError('fuel-type', 'Please select the fuel type.');
    if (!document.querySelector('textarea[name="description"]').value) setError('description', 'Please enter a description.');
    if (!document.querySelector('select[name="condition"]').value) setError('condition', 'Please select the car condition.');
    if (selectedFiles.length === 0) setError('images', 'Please upload at least one image.');

    console.log('Form validation result:', isValid); // Debug log

    if (firstErrorElement) {
        firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    return isValid;
}

// Compress image
function compressImage(file, targetWidth, targetHeight, quality, callback) {
    const reader = new FileReader();
    reader.readAsDataURL(file);

    reader.onload = function (event) {
        const img = new Image();
        img.src = event.target.result;

        img.onload = function () {
            const canvas = document.createElement("canvas");
            canvas.width = targetWidth;
            canvas.height = targetHeight;
            const ctx = canvas.getContext("2d");

            ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

            canvas.toBlob((blob) => {
                const compressedFile = new File([blob], file.name, { type: "image/jpeg", lastModified: Date.now() });
                callback(compressedFile);
            }, "image/jpeg", quality);
        };
    };
}

// Submit form
function submitForm(compressedFiles) {
    const form = document.getElementById('carForm');
    const formData = new FormData(form);
    const progress = document.getElementById('progress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const message = document.getElementById('message');

    // Remove any existing image files from form data
    formData.delete("images[]");
    
    // Ensure custom fields are properly handled
    const makeValue = document.querySelector('select[name="make"]').value;
    const customMakeValue = document.querySelector('input[name="custom_make"]').value;
    const modelValue = document.querySelector('select[name="model"]').value;
    const customModelValue = document.querySelector('input[name="custom_model"]').value;
    const variantValue = document.querySelector('select[name="variant"]').value;
    const customVariantValue = document.querySelector('input[name="custom_variant_input"]').value;
    
    // Set empty values for unused custom fields to prevent server errors
    if (makeValue !== 'Other') {
        formData.set('custom_make', '');
    }
    if (makeValue !== 'Other' && modelValue !== 'Other') {
        formData.set('custom_model', '');
    }
    if (variantValue !== 'Other') {
        formData.set('custom_variant_input', '');
    }

    // Add compressed files in the exact order they were selected
    compressedFiles.forEach((file, index) => {
        // Create a new file with order information in the name for server-side processing
        const orderedFile = new File([file], `${index.toString().padStart(3, '0')}_${file.name}`, {
            type: file.type,
            lastModified: file.lastModified
        });
        formData.append("images[]", orderedFile);
    });

    // Add order metadata to ensure server processes images in correct sequence
    formData.append("image_order", JSON.stringify(compressedFiles.map((file, index) => ({
        index: index,
        originalName: file.name,
        size: file.size
    }))));

    // Debug: Log form data being sent
    console.log('Form data being sent:');
    for (let pair of formData.entries()) {
        if (pair[0] !== 'images[]') { // Don't log image files
            console.log(pair[0] + ': ' + pair[1]);
        }
    }
    console.log('Number of images:', compressedFiles.length);

    if (compressedFiles.length > 0) {
        progress.style.display = 'block';
        progressBar.style.width = '30%'; // Start from 30% after compression
        progressText.textContent = 'Uploading images...';
    }

    const xhr = new XMLHttpRequest();

    if (compressedFiles.length > 0) {
        xhr.upload.addEventListener('progress', function (event) {
            if (event.lengthComputable) {
                // Upload progress starts from 30% (after compression) to 100%
                const uploadProgress = (event.loaded / event.total) * 70; // 70% for upload
                const totalProgress = 30 + uploadProgress;
                progressBar.style.width = totalProgress + '%';
                progressText.textContent = `Uploading: ${Math.round(totalProgress)}%`;
            }
        });
    }

    xhr.addEventListener('load', function () {
        if (xhr.status === 200) {
            try {
                console.log('Server response:', xhr.responseText); // Debug log
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Complete progress
                    progressBar.style.width = '100%';
                    progressText.textContent = 'Upload complete!';
                    
                    message.style.display = 'block';
                    message.innerHTML = `<div class="alert alert-success">${response.message}</div>`;

                    form.reset();
                    selectedFiles = [];
                    displayImages();

                    setTimeout(() => {
                        window.location.href = 'cars.php';
                    }, 3000);
                } else {
                    message.style.display = 'block';
                    message.innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
                }
            } catch (error) {
                console.error("JSON parse error:", error);
                console.error("Raw response:", xhr.responseText);
                message.style.display = 'block';
                message.innerHTML = `<div class="alert alert-danger">Server response error. Please check the console for details.</div>`;
            }
        } else {
            console.error("HTTP Error:", xhr.status, xhr.statusText);
            message.style.display = 'block';
            message.innerHTML = `<div class="alert alert-danger">Server error (${xhr.status}). Please try again.</div>`;
        }

        if (compressedFiles.length > 0) {
            progress.style.display = 'none';
        }
    });

    xhr.addEventListener('error', function () {
        message.style.display = 'block';
        message.innerHTML = `<div class="alert alert-danger">Network error occurred. Please try again.</div>`;
        if (compressedFiles.length > 0) {
            progress.style.display = 'none';
        }
    });

    xhr.open('POST', form.action, true);
    xhr.send(formData);
}

// Drag and drop functionality for reordering images
let draggedElement = null;
let isDragging = false;

function handleDragStart(e) {
    isDragging = true;
    draggedElement = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);
    
    // Add dragging class to the card inside
    const card = this.querySelector('.card');
    if (card) {
        card.classList.add('dragging-card');
    }
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    
    // Add visual feedback for drop target
    if (this !== draggedElement) {
        this.classList.add('drag-over');
    }
    
    return false;
}

function handleDragLeave(e) {
    // Only remove if we're actually leaving this element
    if (!this.contains(e.relatedTarget)) {
        this.classList.remove('drag-over');
    }
}

function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    
    // Remove visual feedback
    this.classList.remove('drag-over');
    
    if (draggedElement !== this) {
        const draggedIndex = parseInt(draggedElement.dataset.index);
        const droppedIndex = parseInt(this.dataset.index);
        
        // Reorder the selectedFiles array
        const draggedFile = selectedFiles[draggedIndex];
        selectedFiles.splice(draggedIndex, 1);
        selectedFiles.splice(droppedIndex, 0, draggedFile);
        
        // Refresh the display
        displayImages();
    }
    
    return false;
}

function handleDragEnd(e) {
    isDragging = false;
    
    // Remove all drag-related classes
    const card = this.querySelector('.card');
    if (card) {
        card.classList.remove('dragging-card');
    }
    this.classList.remove('drag-over');
    draggedElement = null;
    
    // Clean up any remaining visual feedback from all elements
    const gridContainer = document.getElementById('image-grid-container');
    if (gridContainer) {
        const allCols = gridContainer.querySelectorAll('[draggable="true"]');
        allCols.forEach(col => {
            col.classList.remove('drag-over');
            const cardInCol = col.querySelector('.card');
            if (cardInCol) {
                cardInCol.classList.remove('dragging-card');
            }
        });
    }
}
