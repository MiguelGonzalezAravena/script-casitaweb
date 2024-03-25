<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function downloadAvatar(){}
function createThumbnail($source, $max_width, $max_height){}
function resizeImage($src_img, $destName, $src_width, $src_height, $max_width, $max_height){}

function imagecopyresamplebicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
{
	$palsize = imagecolorstotal($src_img);
	for ($i = 0; $i < $palsize; $i++)
	{
		$colors = imagecolorsforindex($src_img, $i);
		imagecolorallocate($dst_img, $colors['red'], $colors['green'], $colors['blue']);
	}

	$scaleX = ($src_w - 1) / $dst_w;
	$scaleY = ($src_h - 1) / $dst_h;

	$scaleX2 = (int) $scaleX / 2;
	$scaleY2 = (int) $scaleY / 2;

	for ($j = $src_y; $j < $dst_h; $j++)
	{
		$sY = (int) $j * $scaleY;
		$y13 = $sY + $scaleY2;

		for ($i = $src_x; $i < $dst_w; $i++)
		{
			$sX = (int) $i * $scaleX;
			$x34 = $sX + $scaleX2;

			$color1 = imagecolorsforindex($src_img, imagecolorat($src_img, $sX, $y13));
			$color2 = imagecolorsforindex($src_img, imagecolorat($src_img, $sX, $sY));
			$color3 = imagecolorsforindex($src_img, imagecolorat($src_img, $x34, $y13));
			$color4 = imagecolorsforindex($src_img, imagecolorat($src_img, $x34, $sY));

			$red = ($color1['red'] + $color2['red'] + $color3['red'] + $color4['red']) / 4;
			$green = ($color1['green'] + $color2['green'] + $color3['green'] + $color4['green']) / 4;
			$blue = ($color1['blue'] + $color2['blue'] + $color3['blue'] + $color4['blue']) / 4;

			$color = imagecolorresolve($dst_img, $red, $green, $blue);
			if ($color == -1)
			{
				if ($palsize++ < 256)
					imagecolorallocate($dst_img, $red, $green, $blue);
				$color = imagecolorclosest($dst_img, $red, $green, $blue);
			}

			imagesetpixel($dst_img, $i + $dst_x - $src_x, $j + $dst_y - $src_y, $color);
		}
	}
}

if (!function_exists('imagecreatefrombmp'))
{
	function imagecreatefrombmp($filename)
	{
		global $gd2;

		$fp = fopen($filename, 'rb');

		$errors = error_reporting(0);

		$header = unpack('vtype/Vsize/Vreserved/Voffset', fread($fp, 14));
		$info = unpack('Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vcolorimportant', fread($fp, 40));

		if ($header['type'] != 0x4D42)
			false;

		if ($gd2)
			$dst_img = imagecreatetruecolor($info['width'], $info['height']);
		else
			$dst_img = imagecreate($info['width'], $info['height']);

		$palette_size = $header['offset'] - 54;
		$info['ncolor'] = $palette_size / 4;

		$palette = array();

		$palettedata = fread($fp, $palette_size);
		$n = 0;
		for ($j = 0; $j < $palette_size; $j++)
		{
			$b = ord($palettedata{$j++});
			$g = ord($palettedata{$j++});
			$r = ord($palettedata{$j++});

			$palette[$n++] = imagecolorallocate($dst_img, $r, $g, $b);
		}

		$scan_line_size = ($info['bits'] * $info['width'] + 7) >> 3;
		$scan_line_align = $scan_line_size & 3 ? 4 - ($scan_line_size & 3) : 0;

		for ($y = 0, $l = $info['height'] - 1; $y < $info['height']; $y++, $l--)
		{
			fseek($fp, $header['offset'] + ($scan_line_size + $scan_line_align) * $l);
			$scan_line = fread($fp, $scan_line_size);

			if (strlen($scan_line) < $scan_line_size)
				continue;

			if ($info['bits'] == 32)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
				{
					$b = ord($scan_line{$j++});
					$g = ord($scan_line{$j++});
					$r = ord($scan_line{$j++});
					$j++;

					$color = imagecolorexact($dst_img, $r, $g, $b);
					if ($color == -1)
					{
						$color = imagecolorallocate($dst_img, $r, $g, $b);

						// Gah!  Out of colors?  Stupid GD 1... try anyhow.
						if ($color == -1)
							$color = imagecolorclosest($dst_img, $r, $g, $b);
					}

					imagesetpixel($dst_img, $x, $y, $color);
				}
			}
			elseif ($info['bits'] == 24)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
				{
					$b = ord($scan_line{$j++});
					$g = ord($scan_line{$j++});
					$r = ord($scan_line{$j++});

					$color = imagecolorexact($dst_img, $r, $g, $b);
					if ($color == -1)
					{
						$color = imagecolorallocate($dst_img, $r, $g, $b);

						// Gah!  Out of colors?  Stupid GD 1... try anyhow.
						if ($color == -1)
							$color = imagecolorclosest($dst_img, $r, $g, $b);
					}

					imagesetpixel($dst_img, $x, $y, $color);
				}
			}
			elseif ($info['bits'] == 16)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
				{
					$b1 = ord($scan_line{$j++});
					$b2 = ord($scan_line{$j++});

					$word = $b2 * 256 + $b1;

					$b = (($word & 31) * 255) / 31;
					$g = ((($word >> 5) & 31) * 255) / 31;
					$r = ((($word >> 10) & 31) * 255) / 31;

					// Scale the image colors up properly.
					$color = imagecolorexact($dst_img, $r, $g, $b);
					if ($color == -1)
					{
						$color = imagecolorallocate($dst_img, $r, $g, $b);

						// Gah!  Out of colors?  Stupid GD 1... try anyhow.
						if ($color == -1)
							$color = imagecolorclosest($dst_img, $r, $g, $b);
					}

					imagesetpixel($dst_img, $x, $y, $color);
				}
			}
			elseif ($info['bits'] == 8)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
					imagesetpixel($dst_img, $x, $y, $palette[ord($scan_line{$j++})]);
			}
			elseif ($info['bits'] == 4)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
				{
					$byte = ord($scan_line{$j++});

					imagesetpixel($dst_img, $x, $y, $palette[(int) ($byte / 16)]);
					if (++$x < $info['width'])
						imagesetpixel($dst_img, $x, $y, $palette[$byte & 15]);
				}
			}
			else
			{
				// Sorry, I'm just not going to do monochrome :P.
			}
		}

		fclose($fp);

		error_reporting($errors);

		return $dst_img;
	}}

function gif_loadFile($lpszFileName, $iIndex = 0){}
function gif_outputAsPng($gif, $lpszFileName, $background_color = -1){}
function showCodeImage($code){}
function showLetterImage($letter){}

?>