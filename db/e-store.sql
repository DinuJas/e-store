-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 22, 2026 at 08:10 PM
-- Server version: 12.1.2-MariaDB
-- PHP Version: 8.5.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-store`
--

-- --------------------------------------------------------

--
-- Table structure for table `baskets`
--

CREATE TABLE `baskets` (
  `basket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('active','ordered') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baskets`
--


-- --------------------------------------------------------

--
-- Table structure for table `basket_products`
--

CREATE TABLE `basket_products` (
  `basket_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basket_products`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE order_items
ADD UNIQUE KEY unique_order_product (order_id, product_id);
--
-- Dumping data for table `order_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `second_name` varchar(255) NOT NULL,
  `card_number` varchar(45) NOT NULL,
  `card_expiry` char(5) NOT NULL,
  `card_cvv` char(4) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `amount` decimal(10,2) DEFAULT NULL,
  `paid_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `product_info` text DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `image`, `name`, `description`, `price`, `product_info`, `stock`) VALUES
(1, 'shimano_nasci_fc.webp', 'Shimano', 'Shimano Nasci Fc\r\n', 1119.00, 'Haspelsnellene i Shimano Nasci FC-serien er utviklet etter moderne krav fra sportsfiskere som krever jevn innsveivning og slitesterke sneller som passer til både fersk- og saltvannsfiske. Dette er en flott snelle som har grå kropp og grå monochrome farge på detaljer. Shimano leverer som alltid robuste sneller med styrke og slitesterkhet for enhver fiskesituasjon.\r\n\r\nCore Protect\r\nG-Free Body\r\nHagane Gear\r\nPropulsion Line Management System\r\nSilentDrive\r\nWaterproof Drag\r\nX-Ship\r\nNasci FC haspelsnellene har egenskaper som CoreProtect for økt robusthet og vannmotstandighet, uten å tilføre ekstra vekt eller ubalanse til snellen.\r\n\r\nShimano sin Hagane girteknologi gir enestående slitestyrke på girene sammenlignet med andre snellegir.\r\n\r\nSilentDrive er samme teknologi som brukes i Shimano sine toppmodeller og gir økt toleranse ned på mikronivå og begrenser unødvendig lyd og vibrasjon under kjøring av fisk.\r\n\r\nNasci FC er utviklet for å levere Shimano sin velkjente kvalitet og har et særeget øye for detaljer som yter når du trenger det som mest.\r\n\r\nCoreProtect\r\nTotal beskyttelse: Konseptet til CoreProtect er enkelt... For å kunne gi vannmotstandighet,  uten å skape en tung rotasjonsfølelse og vibrasjon, er det områder på snellen som krever beskyttelse. Dette er rulleclutchen, snellekroppen og spolen.\r\n\r\nG-Free Body\r\nG-Free Body teknologien er utviklet for å flytte snellens tyngdekraftsenter nærmere stangen. Ved å flytte tyngdepunktet til sportsfiskerens håndposisjon, hjelper G-free body til med å redusere tretthet og forbedre kastekomforten over lenger tid.\r\n\r\nHagane Gear\r\nMed utrolig styrke og holdbarhet sørger Hagane-giret for selve hjertet av haspelsnellen. Shimano sørger for at den nye følelsen av snellen når du fisker første gang holder seg over tid og når du kaster igjen og igjen.\r\n\r\nDet er ingen deler i Hagane giret som er skåret til. Hele overflaten er isteden beregnet helt ned i de minste detaljene ved hjelp av spesiell 3D-design og deretter formet i Shimano sin Kladpresseteknologi. Resultatet er kvalitet, motstandskraft og ytelse.\r\n\r\nDette er resultatet av Shimano sin uendelige arbeid etter holdbarhet, styrke og kraft. Kaldpresset metall fortsetter å representere det grunnleggende konseptet til Shimano-sneller.\r\n\r\nPropulsion Line Management System\r\nDen mest åpenbare komponenten på snellen er den nye leppedesignen på spolen. Gjennom titusenvis av kastetester og datasimuleringer har det blitt fastslått at denne nye leppedesignen gir lengre kasteavstander enn en standard spoleleppedesign, samtidig som den forhindrer at det dannes tilbakeslag og vindknuter.\r\n\r\nStillegående kjøring\r\nFra den grunnleggende ehull-kroppsdesignen til de mest intrikate delene av drivverket, inkludert drivhjulet, snekkeakseltappen og snekkeakselgiret, har hvert stykke blitt omhyggelig gjennomgått og den minste klaringen og slingringen er eliminert til de høyeste toleransene.\r\n\r\nVanntett brems\r\nMed bruk av drag er mot inntrenging av elementet som vann og støv.\r\n\r\nX-skip\r\nX-ship gir forbedret girholdbarhet ved å støtte pinjonghjulet i begge ender med lagre, pinoin-giret opprettholder nøyaktig innretting med drivgiret. Dette betyr at girene forblir i samme posisjon under de tyngste belastningene. Den ekstra fordelen er at friksjonen mellom spolen og giret er eliminert. Dette vil forbedre kasteytelsen til snellen med lettere sluk, og tillate lengre kast.\r\n\r\nCross Carbon Drag (Kun 5000 størrelsen)\r\nDette bremsematerialet gir et bredere utvalg av bremseinnstillinger, sammen med den jevneste Shimano-bremse ytelsen noensinne.\r\n\r\n \r\n\r\nHG - High Gear (Høy utveksling)\r\nXG - Extra High Gear (Høyeste utveksling)\r\nC - Mindre kropp med samme spolestørrelse (c3000 = 2500 kropp + 3000 spole, c5000 = 4000 kropp + 5000 spole)\r\nS - Shallow (Grunnere spole for lengre og mer presise kast med tynn line og små sluker)\r\n \r\n\r\nTekniske spesifikasjoner for hver modell Shimano produserer av NASCI FC (ikke alle er tilgjengelig til en hver tid i Norden)\r\nModell	Vekt	 Girforhold 	Kulelagere 	Maksimal brems	Snør ekapasitet (mm)\r\nNAS1000FC	205	5,0:1	5+1	3 kg	0.18-170/0.20-140/0.25-90\r\nNAS2500FC	240	5,0:1	5+1	9 kg	0.18-290/0.20-240/0.25-160\r\nNAS2500HGFC	240	6,2:1	5+1	9 kg	0.18-290/0.20-240/0.25-160\r\nNAS2500SHGFC	240	6,2:1	5+1	4 kg	0.16-150/0.18-120/0.20-95\r\nNAS4000FC	285	4,7:1	5+1	11 kg	0.25-260/0.30-180/0.35-130\r\nNAS4000XGFC	285	6,2:1	5+1	11 kg	0.25-260/0.30-180/0.35-130\r\nNAS500FC	170	5,6:1           	5+1	3 kg	0.20-110\r\nNAS2000SFC	205	5,0:1	5+1	3 kg	0.14-145/0.16-105/0.18-80\r\nNAS2000SHGFC	205	6,0:1	5+1	3 kg	0.14-145/0.16-105/0.18-80\r\nNAS3000FC	240	5,0:1	5+1	9 kg	0.25-210/0.30-130/0.35-100\r\nNASC3000HGFC	240	6,2:1	5+1	9 kg	0.25-210/0.30-130/0.35-100\r\nNASC5000XGFC	305	6,2:1	5+1	11 kg	0.30-240/0.35-175/0.40-120\r\n', 10),
(2, 'lawson_northern_lite_x3.webp', 'Lawson', 'Lawson Northern Lite X3\r\n', 1999.00, 'Lawson Northern Lite X3 er vår tredje utgave av denne populære stangserien.\r\n\r\n \r\n\r\nX3 Serien har fått en ansiktsløftning som består av ny finish, farge og med en ny forsterket avslutning av gummikork for bedre holdbarhet og grep.\r\n\r\nDen tredelte klingen er fortsatt 36 tonns karbon som gir en utrolig smekker og spenstig stang med mye kraft. Snellefestet er av type screw down med ergonomisk utforming som sikrer et behagelig grep rundt snelle og stang. (opp til og med 11 fot).\r\n\r\nPå lengdene over 11 fot er det fortsatt screw down , men med den klassiske varianten av snellefeste. På trigger stengene er det et standard snellefeste, men med 13 cm frontgrep av kork. Dette gir et sikkert og behagelig grep om stanga under de hardeste fightene.\r\n\r\nStangringene er lette og smekre av typen SIC i Gun Smoke finish. Denne 3-delte serien består av totalt 19 stenger fra 8 fot til 15 fot og dekker det aller meste av vårt kastefiske i ferskvann og saltvann. Enten det er abbor, ørret, laks, torsk eller sei, stor eller liten, lett eller tungt fiske, vil du finne en stang i denne flotte serien som er egnet til nettopp dette… og mye mer.\r\n\r\n \r\n\r\nModell\r\n\r\nSlukvekt\r\n\r\nDeler\r\n\r\nNorthern Lite X3 8\'\r\n\r\n3-15g\r\n\r\n3\r\n\r\nNorthern Lite X3 9\'\r\n\r\n3-15g\r\n\r\n3\r\n\r\nNorthern Lite X3 9\'\r\n\r\n7-28g\r\n\r\n3\r\n\r\nNorthern Lite X3 9\'\r\n\r\n20-60g\r\n\r\n3\r\n\r\nNorthern Lite X3 9,6\'\r\n\r\n7-24g\r\n\r\n3\r\n\r\nNorthern Lite X3 10\'\r\n\r\n2-15g\r\n\r\n3\r\n\r\nNorthern Lite X3 10\'\r\n\r\n10-35g\r\n\r\n3\r\n\r\nNorthern Lite X3 10\'\r\n\r\n30-80g\r\n\r\n3\r\n\r\nNorthern Lite X3 11\'\r\n\r\n10-50g\r\n\r\n3\r\n\r\nNorthern Lite X3 11\'\r\n\r\n20-70g\r\n\r\n3\r\n\r\nNorthern Lite X3 12\'\r\n\r\n30-70g\r\n\r\n3\r\n\r\nNorthern Lite X3 13\'\r\n\r\n7-32g\r\n\r\n3\r\n\r\nNorthern Lite X3 13\'\r\n\r\n30-90g\r\n\r\n3\r\n\r\nNorthern Lite X3 15\'\r\n\r\n10-50g\r\n\r\n3\r\n\r\nNorthern Lite X3 15\'\r\n\r\n30-100g\r\n\r\n3\r\n\r\nNorthern Lite X3 Trigger 11\'\r\n\r\n20-60g\r\n\r\n3\r\n\r\nNorthern Lite X3 Trigger 12\'\r\n\r\n30-70g\r\n\r\n3\r\n\r\nNorthern Lite X3 Trigger 13\'\r\n\r\n30-90g\r\n\r\n3\r\n\r\nNorthern Lite X3 Trigger 13\'\r\n\r\n40-140g\r\n\r\n3\r\n\r\n', 10),
(3, 'fireline_fused_original_150m_crystal.webp', 'Berkley ', 'Fireline Fused Original 150m Smoke\r\n', 199.00, 'Berkley FireLine Fused Original 150m smoke er ny og forbedret FireLine multifilament fiskesene som er jevnere, tøffere og mer følsom enn tidligere versjoner. Med 8-tråder som er flettet og smeltet sammen med moderne teknologi, gir det en uovertruffen slitestyrke som gjør den 5-ganger tøffere enn vanlige flettelinjer. Den har en jevnere og mer smidig følelse ved utkast og innsveivning.\r\n\r\n5 ganger mer slitebestandig vanlige flettede fiskesnører\r\nFused 8 Strand Superline gir uovertruffen robusthet og slitasje motstand\r\nLangtkastende og utrolig følsom\r\nEgenskaper ved Fireline Fused Original 150m Smoke\r\nDiameter	Bruddstyrke	Lengde	Farge\r\n0,10mm	6,2kg	150m	Smoke\r\n0,12mm	7,2kg	150m	Smoke\r\n0,15mm	8,3kg	150m	Smoke\r\n0,17mm	10,7kg	150m	Smoke\r\n0,20mm	13,9kg	150m	Smoke\r\n0,25mm	18,4kg	150m	Smoke\r\n0,32mm	26,6kg	150m	Smoke\r\n0,39mm	29,1kg	150m	Smoke', 97),
(4, 'fierce_thermo_suit.webp', 'Penn', 'Fierce Thermo Suit\r\n', 1199.00, 'Fierce Thermo Suit er et isolert sett bestående av jakke og bukse, designet spesielt med tanke på fiske. Enten du fisker fra brygga eller båten i rufsete vær sørger dette settet for å holde deg varm, tørr og komfortabel, takket være isolering og membran. \r\n\r\nVannavvisende brystlomme\r\nJusterbar hette og mansjett\r\nStore, frontlommer med glidelås og fleecefôr\r\nFyll: Lett, 160g/m2 poly fill\r\nFôr: Microfleece\r\nMateriale: 100% polyester med PVC-belegg, 8.000mm', 54);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `baskets`
--
ALTER TABLE `baskets`
  ADD PRIMARY KEY (`basket_id`),
  ADD KEY `fk_baskets_users` (`user_id`);

--
-- Indexes for table `basket_products`
--
ALTER TABLE `basket_products`
  ADD PRIMARY KEY (`basket_id`,`product_id`),
  ADD KEY `fk_bp_products` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_users` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_oi_orders` (`order_id`),
  ADD KEY `fk_oi_products` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payments_orders` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `baskets`
--
ALTER TABLE `baskets`
  MODIFY `basket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baskets`
--
ALTER TABLE `baskets`
  ADD CONSTRAINT `fk_baskets_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `basket_products`
--
ALTER TABLE `basket_products`
  ADD CONSTRAINT `fk_bp_baskets` FOREIGN KEY (`basket_id`) REFERENCES `baskets` (`basket_id`),
  ADD CONSTRAINT `fk_bp_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_oi_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_oi_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
