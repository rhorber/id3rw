# ID3rw

> PHP-Library for reading and writing ID3 tags.

> ⚠ The Library yet only supports parsing of Version 2.3.0 and Version 2.4.0, and writing Version 2.4.0.

> ℹ The library is in early development stage, but if possible the current API (public methods) will not change.

This project adheres to [Semantic Versioning](https://semver.org/).


## Example

```php
// Read frames from file.
$reader = new \Rhorber\ID3rw\Reader('/home/user/myfile.mp3');
$frames = $reader->getFrames();
print_r($frames);

// Change title.
$frames['TIT2']['content'] = '2018-10-21_'.$frames['TIT2']['content'];

// Write a new file (with modified title).
$writer = new \Rhorber\ID3rw\Writer();
$writer->writeNewFiel($frames, '/home/user/mynewfile.mp3');
```


## License

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details


## TODO

Features I will add:
* Add more writing variants and expand their docs.
* Support more versions (at least V2.3.0).
* Improve parsing of non-text frames.
* Return detailed info about frames (not only technical name).
* Improve readme.

- Add names and/or descriptions to the frames.
- Add the lists of frames (TCON, TFLT, TMED (TKEY), ETCO, APIC)
- Add notices when displaying TCOP and TPRO

=> rename index `content`, maybe to `parsed` (and move optional `encoding` into it).

## About/History

I needed a library for modifying some ID3 tags.
I did not find any library for doing that (getid3 was not able to write correctly),
so I started writing my own in plain PHP. 

## Version 2.3.0

The following frames can be parsed the same way is in version 2.4.0
- ETCO
- MCDI
- TXXX
- UFID
- URL link frames (W000 - WZZZ)
- WXXX
