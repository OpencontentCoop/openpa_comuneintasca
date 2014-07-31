<?php
class CITEventsImportHandler extends SQLIImportAbstractHandler implements ISQLIImportHandler
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
        //classificationsIt
        //classificationsDe
        //classificationsEn
        //nameIt
        //nameDe
        //nameEn
        //addressIt
        //addressDe
        //addressEn
        //urlPage
        //email
        //phone
        //relatedContentId
        //pictureUrl
        //shortDescriptionIt
        //shortDescriptionDe
        //shortDescriptionEn
        //lastModificationDate
        //pictureWidth
        //latitude
        //longitude
        //startDate
        //endDate
        //eventDateDescriptionIt
        //eventDateDescriptionDe
        //eventDateDescriptionEn

        $this->currentGUID = $remote_id = $row->id;
        
        $from = DateTime::createFromFormat( "d/m/Y H:i a O", (string) $row->startDate );
        $to = DateTime::createFromFormat( "d/m/Y H:i a O", (string) $row->endDate );
        
        $contentOptions = new SQLIContentOptions( array(
            'class_identifier'      => 'iniziativa',
            'remote_id'				=> $remote_id,
            'language'              => 'ita-IT'
        ) );
        $content = SQLIContent::create( $contentOptions );
        $content->fields->titolo = (string) $row->nameIt;		
        $content->fields->abstract = SQLIContentUtils::getRichContent( (string) $row->shortDescriptionIt );        
        $content->fields->image = self::getImage( (string) $row->pictureUrl );
        $content->fields->periodo_svolgimento = (string) $row->eventDateDescriptionIt;	
        $content->fields->luogo_svolgimento = (string) $row->addressIt;	        
        $content->fields->url = (string) $row->urlPage;	
        $content->fields->email = (string) $row->email;	
        $content->fields->telefono = (string) $row->phone;
        if ( (string) $row->latitude != '' )
            $content->fields->geo = '1|#' . $row->latitude . '|#' . $row->longitude . '|#' . $row->addressIt;
        else
            $content->fields['eng-GB']->geo = 0;
        
        
        $content->addTranslation( 'eng-GB' );
        $content->fields['eng-GB']->titolo = (string) $row->nameEn;		
        $content->fields['eng-GB']->abstract = SQLIContentUtils::getRichContent( (string) $row->shortDescriptionEn );        
        $content->fields->image = self::getImage( (string) $row->pictureUrl );        
        $content->fields['eng-GB']->periodo_svolgimento = (string) $row->eventDateDescriptionEn;	
        $content->fields['eng-GB']->luogo_svolgimento = (string) $row->addressEn;	        
        $content->fields['eng-GB']->url = (string) $row->urlPage;	
        $content->fields['eng-GB']->email = (string) $row->email;	
        $content->fields['eng-GB']->telefono = (string) $row->phone;
        if ( (string) $row->latitude != '' )
            $content->fields['eng-GB']->geo = '1|#' . $row->latitude . '|#' . $row->longitude . '|#' . $row->addressEn;
        else
            $content->fields['eng-GB']->geo = 0;

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
