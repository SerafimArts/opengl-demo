<?php

declare(strict_types=1);

namespace Bic\Image\Decoder\IcoDecoder;

/**
 * The BITMAPINFOHEADER structure contains information about the dimensions and
 * color format of a device-independent bitmap (DIB).
 *
 * Note: This structure is also described in the GDI documentation. However,
 * the semantics for video data are slightly different than the semantics used
 * for GDI. If you are using this structure to describe video data, use the
 * information given here.
 *
 * @link https://learn.microsoft.com/en-us/windows/win32/api/wingdi/ns-wingdi-bitmapinfoheader
 */
final class BitMapInfoHeader
{
    /**
     * Structure size in bytes.
     *
     * @var positive-int
     */
    public const BYTES_SIZE = 40;

    /**
     * @param positive-int $size Specifies the number of bytes required by the structure. This value does not include
     *                           the size of the color table or the size of the color masks, if they are appended to the
     *                           end of structure.
     * @param int $width Specifies the width of the bitmap, in pixels.
     * @param int $height Specifies the height of the bitmap, in pixels.
     *                    - For uncompressed RGB bitmaps, if {@see self::$height} is positive, the bitmap is a bottom-up
     *                      DIB with the origin at the lower left corner. If {@see self::$height} is negative, the
     *                      bitmap is a top-down DIB with the origin at the upper left corner.
     *                    - For YUV bitmaps, the bitmap is always top-down, regardless of the sign of
     *                      {@see self::$height}. Decoders should offer YUV formats with positive {@see self::$height},
     *                      but for backward compatibility they should accept YUV formats with either positive or
     *                      negative {@see self::$height}.
     *                    - For compressed formats, {@see self::$height} must be positive, regardless of image
     *                      orientation.
     * @param positive-int $planes Specifies the number of planes for the target device. This value must be set to 1.
     * @param positive-int $bitCount Specifies the number of bits per pixel (bpp). For uncompressed formats, this value
     *                               is the average number of bits per pixel. For compressed formats, this value is the
     *                               implied bit depth of the uncompressed image, after the image has been decoded.
     * @param Compression $compression For compressed video and YUV formats, this member is a FOURCC code, specified as
     *                                 a DWORD in little-endian order. For example, YUYV video has the FOURCC 'VYUY' or
     *                                 0x56595559.
     *
     *                                  For uncompressed RGB formats, the following values are possible:
     *                                  - 0x0000 (BI_RGB) - Uncompressed RGB.
     *                                  - 0x0003 (BI_BITFIELDS) - Uncompressed RGB with color masks.
     *                                    Valid for 16-bpp and 32-bpp bitmaps.
     *
     *                                  For 16-bpp bitmaps, if biCompression equals BI_RGB, the format is always RGB555.
     *                                  If {@see self::$compression} equals BI_BITFIELDS, the format is either RGB555 or
     *                                  RGB565. Use the subtype GUID in the AM_MEDIA_TYPE structure to determine the
     *                                  specific RGB type.
     * @param positive-int $sizeImage Specifies the size, in bytes, of the image. This can be set to 0 for uncompressed
     *                                RGB bitmaps.
     * @param int $xPelsPerMeter Specifies the horizontal resolution, in pixels per meter, of the target device for the
     *                           bitmap.
     * @param int $yPelsPerMeter Specifies the vertical resolution, in pixels per meter, of the target device for the
     *                           bitmap.
     * @param positive-int $clrUsed Specifies the number of color indices in the color table that are actually used by
     *                              the bitmap.
     * @param positive-int $clrImportant Specifies the number of color indices that are considered important for
     *                                   displaying the bitmap. If this value is zero, all colors are important.
     *
     * - FOURCC: @link https://learn.microsoft.com/en-us/windows/win32/directshow/fourcc-codes
     * - Compression Types: @link https://learn.microsoft.com/en-us/openspecs/windows_protocols/ms-wmf/4e588f70-bd92-4a6f-b77f-35d0feaf7a57
     * - AM_MEDIA_TYPE struct: @link https://learn.microsoft.com/en-us/windows/win32/api/strmif/ns-strmif-am_media_type
     */
    public function __construct(
        public readonly int $size,
        public readonly int $width,
        public readonly int $height,
        public readonly int $planes,
        public readonly int $bitCount,
        public readonly Compression $compression,
        public readonly int $sizeImage,
        public readonly int $xPelsPerMeter,
        public readonly int $yPelsPerMeter,
        public readonly int $clrUsed,
        public readonly int $clrImportant,
    ) {
        assert($planes === 1);
    }
}
