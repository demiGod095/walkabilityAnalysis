package org.mccaughey;

import java.io.File;
import java.io.IOException;
import java.net.URISyntaxException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

//import org.apache.log4j.Level;
//import org.apache.log4j.Logger;
import org.geotools.data.DataUtilities;
import org.geotools.data.FileDataStore;
import org.geotools.data.FileDataStoreFinder;
import org.geotools.data.simple.SimpleFeatureCollection;
import org.geotools.data.simple.SimpleFeatureIterator;
import org.mccaughey.connectivity.ConnectivityIndexOMS;
import org.mccaughey.connectivity.NetworkBufferOMS;
import org.mccaughey.density.DwellingDensityOMS;
import org.mccaughey.landuse.LandUseMixOMS;
import org.mccaughey.statistics.ZScoreOMS;
import org.mccaughey.utilities.GeoJSONUtilities;
import org.opengis.feature.simple.SimpleFeature;

import au.org.aurin.types.AttributeSelector;

public class MainJarOMS
{
	public static void usage()
	{
		System.out.println( "java -jar walkability.jar roads_file points_file mesh_file [out_folder]" );
		System.out.println( "\troads_file:\tgeojson line features" );
		System.out.println( "\tpoints_file:\tgeojson point features" );
		System.out.println( "\tmesh_file:\tshp polygon features with dwelling counts and use catagory" );
		System.out.println( "\t[out_folder]:\tfolder to put the output files into, Defaults to 'output'" );
	}

	public static void main( String[] args )
	{
		//Logger.getRootLogger().setLevel(Level.OFF);
		if( args.length < 3 )
		{
			usage();
			System.exit( 1 );
		}

		/*
		 * Start recording time
		 */
		long startTime = System.currentTimeMillis();
		long stepTimeS = startTime;
		long stepTimeE;
		try
		{
			// program config
			String dwellAttrib = "person";
			String classifAttrib = "mb_categor";
			double buffSize = 50.0;
			double dist = 600.0;

			/*
			 * Step 1. Declare input files:
			 */

			String roadsFileLoc = args[0];
			String pointsFileLoc = args[1];
			String landFileLoc = args[2];

			String opDirName = "output";

			if( args.length > 3 )
			{
				opDirName = args[3];
			}

			File opDir = new File( opDirName );
			opDir.mkdirs();
			

			URL roadsUrl = new File( roadsFileLoc ).toURI().toURL();
			URL pointsUrl = new File( pointsFileLoc ).toURI().toURL();
			URL landUseURL = new File( landFileLoc ).toURI().toURL();

			/*
			 * Step 2. Generate polygon regions.
			 */

			System.out.println( "Generating buffers..." );
			NetworkBufferOMS networkBufferOMS = new NetworkBufferOMS();
			networkBufferOMS.network = DataUtilities.source( GeoJSONUtilities.readFeatures( roadsUrl ) );
			networkBufferOMS.points = DataUtilities.source( GeoJSONUtilities.readFeatures( pointsUrl ) );
			networkBufferOMS.bufferSize = buffSize;
			networkBufferOMS.distance = dist;
			networkBufferOMS.run();
			GeoJSONUtilities.writeFeatures( networkBufferOMS.regions.getFeatures(),
					new File( opDirName + File.separator + "networkBufferOMSTest.geojson" ).toURI().toURL() );

			stepTimeE = System.currentTimeMillis();
			System.out.println( "Buffer generated in: " + (double) (stepTimeE - stepTimeS) / 1000.0 + "s" );
			stepTimeS = stepTimeE;
			/*
			 * Step 3. Read in the file generated in the last step. Calculate
			 * the Connectivity Index.
			 */

			System.out.println( "Calculating connectivity..." );
			URL regionsUrl = new File( opDirName + File.separator + "networkBufferOMSTest.geojson" ).toURI().toURL();
			ConnectivityIndexOMS connectivityOMS = new ConnectivityIndexOMS();
			connectivityOMS.network = DataUtilities.source( GeoJSONUtilities.readFeatures( roadsUrl ) );
			connectivityOMS.regions = DataUtilities.source( GeoJSONUtilities.readFeatures( regionsUrl ) );
			connectivityOMS.run();
			GeoJSONUtilities.writeFeatures( connectivityOMS.results.getFeatures(),
					new File( opDirName + File.separator + "connectivityOMSTest.geojson" ).toURI().toURL() );

			stepTimeE = System.currentTimeMillis();
			System.out.println( "Connectivity calculated in: " + (double) (stepTimeE - stepTimeS) / 1000.0 + "s" );
			stepTimeS = stepTimeE;

			/*
			 * Step 4. Similarly, calculate Density Index.
			 */
			System.out.println( "Calculating density index..." );
			File landUseShapeFile = new File( landUseURL.toURI() );
			File densityGeoJSON = File.createTempFile( "density", ".geojson" );
			FileDataStore densityDataStore = FileDataStoreFinder.getDataStore( landUseShapeFile );
			GeoJSONUtilities.writeFeatures( densityDataStore.getFeatureSource().getFeatures(),
					densityGeoJSON.toURI().toURL() );

			DwellingDensityOMS densityOMS = new DwellingDensityOMS();

			densityOMS.countAttribute = new AttributeSelector( null, dwellAttrib );
			densityOMS.populationSource = DataUtilities
					.source( GeoJSONUtilities.readFeatures( densityGeoJSON.toURI().toURL() ) );
			densityOMS.regionsSource = DataUtilities.source( GeoJSONUtilities.readFeatures( regionsUrl ) );
			densityOMS.averageDensity();
			GeoJSONUtilities.writeFeatures( densityOMS.resultsSource.getFeatures(),
					new File( opDirName + File.separator + "densityOMSTest.geojson" ).toURI().toURL() );

			densityDataStore.dispose();
			densityGeoJSON.delete();

			stepTimeE = System.currentTimeMillis();
			System.out.println( "Density calculated in: " + (double) (stepTimeE - stepTimeS) / 1000.0 + "s" );
			stepTimeS = stepTimeE;

			/*
			 * Step 5. Calculate LandUseMix Index.
			 */
			System.out.println( "Calculating LUM index..." );
			FileDataStore landUseDataStore = FileDataStoreFinder.getDataStore( landUseShapeFile );

			List<String> classifications = new ArrayList<String>();
			classifications.add( "Parkland" );
			// classifications.add( "Water" );
			// classifications.add( "Primary Production" );
			// classifications.add( "Transport" );
			classifications.add( "Commercial" );
			// classifications.add( "Other" );
			classifications.add( "Hospital/Medical" );
			classifications.add( "Residential" );
			classifications.add( "Industrial" );
			classifications.add( "Education" );

			LandUseMixOMS lumOMS = new LandUseMixOMS();
			lumOMS.landUseSource = landUseDataStore.getFeatureSource();
			lumOMS.regionsSource = DataUtilities.source( GeoJSONUtilities.readFeatures( regionsUrl ) );
			lumOMS.classificationAttribute = classifAttrib;
			lumOMS.categories = classifications;
			lumOMS.validateInputs();
			lumOMS.landUseMixMeasure();

			GeoJSONUtilities.writeFeatures( lumOMS.resultsSource.getFeatures(),
					new File( opDirName + File.separator + "lumOMSTest.geojson" ).toURI().toURL() );
			landUseDataStore.dispose();

			stepTimeE = System.currentTimeMillis();
			System.out.println( "LUM index calculated in: " + (double) (stepTimeE - stepTimeS) / 1000.0 + "s" );
			stepTimeS = stepTimeE;

			/*
			 * Step 6. Read in the results generated above (Connectivity,
			 * Density, LUM). Merge them into one feature file
			 */
			System.out.println( "Merging data..." );
			List<SimpleFeature> ZScoreFeatures = new ArrayList<>();
			URL connectivityUrl = new File( opDirName + File.separator + "connectivityOMSTest.geojson" ).toURI()
					.toURL();
			URL densityUrl = new File( opDirName + File.separator + "densityOMSTest.geojson" ).toURI().toURL();
			URL lumUrl = new File( opDirName + File.separator + "lumOMSTest.geojson" ).toURI().toURL();

			SimpleFeatureIterator connectivityIt = DataUtilities
					.source( GeoJSONUtilities.readFeatures( connectivityUrl ) ).getFeatures().features();
			SimpleFeatureIterator densityIt = DataUtilities.source( GeoJSONUtilities.readFeatures( densityUrl ) )
					.getFeatures().features();
			SimpleFeatureIterator lumIt = DataUtilities.source( GeoJSONUtilities.readFeatures( lumUrl ) ).getFeatures()
					.features();
			SimpleFeatureIterator regionIt = DataUtilities.source( GeoJSONUtilities.readFeatures( regionsUrl ) )
					.getFeatures().features();

			while( regionIt.hasNext() )
			{
				SimpleFeature region = regionIt.next();
				try
				{
					SimpleFeature conn = connectivityIt.next();
					SimpleFeature den = densityIt.next();
					SimpleFeature lumix = lumIt.next();

					if( !region.getID().equalsIgnoreCase( den.getID() ) )
					{
						conn = connectivityIt.next();
						lumix = lumIt.next();
						region = regionIt.next();
					}

					Double connectivity = (Double) conn.getAttribute( "Connectivity" );
					Double density = (Double) den.getAttribute( "AverageDensity" );
					Double lum = (Double) lumix.getAttribute( "LandUseMixMeasure" );
					ZScoreFeatures.add( Config.buildFeature( region, connectivity, density, lum ) );
				}
				catch( Exception e )
				{
					System.out.println( "Skipped region due to error: " + region.getID() );
					// e.printStackTrace();
				}
			}

			List<String> attributes = new ArrayList<String>();
			attributes.add( "Connectivity" );
			attributes.add( "Density" );
			attributes.add( "LUM" );

			SimpleFeatureCollection ZScoreCollections = DataUtilities.collection( ZScoreFeatures );
			GeoJSONUtilities.writeFeatures( ZScoreCollections,
					new File( opDirName + File.separator + "zScoreOMSTest.geojson" ).toURI().toURL() );

			stepTimeE = System.currentTimeMillis();
			System.out.println( "Data merged in: " + (double) (stepTimeE - stepTimeS) / 1000.0 + "s" );
			stepTimeS = stepTimeE;
			/*
			 * Step 7. Read in the data file above. Calculate the Sum-Zcore for
			 * each region.
			 */

			System.out.println( "Calculating Z Score..." );
			URL zScoreUrl = new File( opDirName + File.separator + "zScoreOMSTest.geojson" ).toURI().toURL();
			ZScoreOMS zscoreOMS = new ZScoreOMS();
			zscoreOMS.attributes = attributes;
			zscoreOMS.regionsSource = DataUtilities.source( GeoJSONUtilities.readFeatures( zScoreUrl ) );
			zscoreOMS.sumOfZScores();

			GeoJSONUtilities.writeFeatures( zscoreOMS.resultsSource.getFeatures(),
					new File( opDirName + File.separator + "ZScoreResult.geojson" ).toURI().toURL() );
			stepTimeE = System.currentTimeMillis();
			System.out.println( "Z-score calculated in: " + (double) (stepTimeE - stepTimeS) / 1000.0 + "s" );
		}
		catch( URISyntaxException e1 )
		{
			System.out.println( e1.getMessage() );
		}
		catch( IOException e2 )
		{
			System.out.println( e2.getMessage() );
		}
		long endTime = System.currentTimeMillis();
		System.out.println( "Done!\nTotal time taken: " + (double) (endTime - startTime) / 1000.0 + "s" );
	}
}
