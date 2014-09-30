<?php
class CITItineraryImportHandler extends SQLIImportAbstractHandler implements ISQLIImportHandler
{
    protected $rowIndex = 0;

    protected $rowCount;

    protected $currentGUID;

    public function __construct( SQLIImportHandlerOptions $options = null )
    {
        parent::__construct( $options );
        $this->remoteIDPrefix = $this->getHandlerIdentifier().'-';
        $this->currentRemoteIDPrefix = $this->remoteIDPrefix;
        $this->options = $options;
    }

    public function initialize()
    {
		$this->tree = array();
        $csvFile = $this->handlerConfArray['CsvFile'];
        $options = new SQLICSVOptions( array(
			'csv_path' => $csvFile,
            'delimiter' => ';',
			'enclosure'   => '"'
		) );
        $csvDoc = new SQLICSVDoc( $options );        
		$csvDoc->parse();        
        $this->dataSource = $csvDoc->rows;   
    }

    public function getProcessLength()
    {
        if( !isset( $this->rowCount ) )
        {
            $this->rowCount = count( $this->dataSource );
            $this->maxRowCount = $this->rowCount;
        }		
        return $this->rowCount;
    }

    public function getNextRow()
    {
        if( $this->rowIndex < $this->rowCount )
        {
            $row = $this->dataSource[$this->rowIndex];
            $this->rowIndex++;
        }
        else
        {
            $row = false; // We must return false if we already processed all rows
        }
        return $row;
    }

    public function process( $row )
    {                
        //id
        //mainLocale
        //categoryName
        //nameIt
        //nameDe
        //nameEn
        //subtitleIt
        //subtitleDe
        //subtitleEn
        //pictureUrl
        //steps
        //lengthM
        //durationMin
        //difficultyLowMediumHigh
        //infoIt
        //infoDe
        //infoEn
        //htmlDescriptionIt
        //htmlDescriptionDe
        //htmlDescriptionEn
        //lastModificationDate        

        $this->currentGUID = $remote_id = $row->id;
        
        $contentOptions = new SQLIContentOptions( array(
            'class_identifier'      => 'itinerario',
            'remote_id'				=> $remote_id,
            'language'              => 'ita-IT'
        ) );
        $content = SQLIContent::create( $contentOptions );
        $content->fields->titolo = (string) $row->nameIt;		
        $content->fields->sottotitolo = (string) $row->subtitleIt;		
        $content->fields->image = self::getImage( (string) $row->pictureUrl );
        $content->fields->steps = $this->steps( (string) $row->steps );
        $content->fields->lunghezza = (string) $row->lengthM;		
        $content->fields->durata = (string) $row->durationMin;		
        $content->fields->difficolta = $this->difficolta( (string) $row->difficultyLowMediumHigh );		
        $content->fields->info = (string) $row->infoIt;		        
        $content->fields->descrizione = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionIt ); 
                
        $content->addTranslation( 'eng-GB' );
        $content->fields['eng-GB']->titolo = (string) $row->nameEn;		
        $content->fields['eng-GB']->sottotitolo = (string) $row->subtitleEn;		
        $content->fields['eng-GB']->image = self::getImage( (string) $row->pictureUrl );
        $content->fields['eng-GB']->steps = $this->steps( (string) $row->steps );
        $content->fields['eng-GB']->lunghezza = (string) $row->lengthM;		
        $content->fields['eng-GB']->durata = (string) $row->durationMin;		
        $content->fields['eng-GB']->difficolta = $this->difficolta( (string) $row->difficultyLowMediumHigh );		
        $content->fields['eng-GB']->info = (string) $row->infoEn;		        
        $content->fields['eng-GB']->descrizione = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionEn );
        
        $content->addTranslation( 'ger-DE' );
        $content->fields['ger-DE']->titolo = (string) $row->nameDe;		
        $content->fields['ger-DE']->sottotitolo = (string) $row->subtitleDe;		
        $content->fields['ger-DE']->image = self::getImage( (string) $row->pictureUrl );
        $content->fields['ger-DE']->steps = $this->steps( (string) $row->steps );
        $content->fields['ger-DE']->lunghezza = (string) $row->lengthM;		
        $content->fields['ger-DE']->durata = (string) $row->durationMin;		
        $content->fields['ger-DE']->difficolta = $this->difficolta( (string) $row->difficultyLowMediumHigh );		
        $content->fields['ger-DE']->info = (string) $row->infoDe;		        
        $content->fields['ger-DE']->descrizione = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionDe );         

        $parentNodeId = $this->handlerConfArray['DefaultParentNodeID'];
        $content->addLocation( SQLILocation::fromNodeID( $parentNodeId ) );
        $publisher = SQLIContentPublisher::getInstance();
        $publisher->publish( $content );
        unset( $content );
    }
    
    public static function getImage( $string )
    {
        return '/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/images/' . $string;
    }

    protected function steps( $string )
    {
        $steps = explode( ',', $string );
        $ids = array();
        foreach( $steps as $step )
        {
            $object = eZContentObject::fetchByRemoteID( trim( $step ), false );
            if ( isset( $object['id'] ) )
            {
                $ids[] = $object['id'];
            }
        }
        return implode( '-', $ids );
    }
    
    protected function difficolta( $string )
    {
        switch( strtolower( $string ) )
        {
            case 'low':
                return 'bassa';
            case 'medium':
                return 'media';
            case 'high':
                return 'alta';
        }
    }
    
    public function cleanup()
    {
        return;
    }

    public function getHandlerName()
    {
        return 'Comune In Tasca Import Handler';
    }

    public function getHandlerIdentifier()
    {
        return 'citimporthandler';
    }

    public function getProgressionNotes()
    {
        return '';
    }
}
