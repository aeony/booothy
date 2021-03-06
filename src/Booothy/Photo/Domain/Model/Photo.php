<?php

namespace Booothy\Photo\Domain\Model;

use DateTimeImmutable;
use Booothy\Photo\Domain\Model\ValueObject\Id;
use Booothy\Photo\Domain\Model\ValueObject\ImageDetails;
use Booothy\Photo\Domain\Model\ValueObject\Quote;
use Booothy\Photo\Domain\Model\ValueObject\Upload;
use Booothy\User\Domain\Model\ValueObject\Email;

final class Photo
{
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    private $id;
    private $quote;
    private $upload;
    private $image_details;
    private $creation_date;
    private $user_id;

    public function __construct(
        Id $an_id,
        Quote $a_quote,
        Upload $an_upload,
        ImageDetails $some_image_details,
        DateTimeImmutable $a_creation_date,
        Email $an_email
    ) {
        $this->id = $an_id;
        $this->quote = $a_quote;
        $this->upload = $an_upload;
        $this->image_details = $some_image_details;
        $this->creation_date = $a_creation_date;
        $this->user_id = $an_email;
    }

    public static function generateNew(
        $a_quote,
        $an_upload_mime_type,
        Email $an_email
    ) {
        $id = Id::next();
        $quote = new Quote($a_quote);
        $creation_date = new DateTimeImmutable;
        $image_details = ImageDetails::fake();
        $upload = self::generateUpload(
            $a_quote,
            $creation_date,
            $an_upload_mime_type
        );

        return new self($id, $quote, $upload, $image_details, $creation_date, $an_email);
    }

    private static function generateUpload(
        $a_quote,
        $a_creation_date,
        $an_upload_mime_type
    ) {
        $filename = self::generateFilename(
            $a_quote,
            $a_creation_date,
            $an_upload_mime_type
        );

        return Upload::atProcessing($filename, $an_upload_mime_type);
    }

    private static function generateFilename($quote, $date, $mime_type)
    {
        $formatted_date = $date->format('Y-m-d_H:i:s');
        $sanitized_spaced_quote = strtolower(preg_replace('/[^a-zA-Z0-9 ]+/', '', $quote));
        $sanitized_quote = str_replace(' ', '-', $sanitized_spaced_quote);
        $extensions = [
            'image/gif'  => '.gif',
            'image/jpeg' => '.jpg',
            'image/png'  => '.png',
        ];

        return $formatted_date . '_' . $sanitized_quote . $extensions[$mime_type];
    }

    public function isStoredIn($storage_provider)
    {
        $this->upload = Upload::atBooothy(
            $this->upload()->filename(),
            $this->upload()->mimeType()
        );
    }

    public function isDetailedAs($hex_color, $width, $height)
    {
        $this->image_details = new ImageDetails($hex_color, $width, $height);
    }

    public function id()
    {
        return $this->id;
    }

    public function quote()
    {
        return $this->quote;
    }

    public function upload()
    {
        return $this->upload;
    }

    public function imageDetails()
    {
        return $this->image_details;
    }

    public function createdAt()
    {
        return $this->creation_date->format(self::DATE_TIME_FORMAT);
    }

    public function createdBy()
    {
        return $this->user_id;
    }
}
