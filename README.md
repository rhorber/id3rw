# ID3rw

> PHP-Library for reading and writing ID3 tags.

> ℹ The Library supports versions 2.3.0 and 2.4.0.

> ℹ The library is in early development stage, but if possible the current API (public methods) will not change.

> ⚠ As I have limited time to work on this library it progresses slowly.
> If you have any suggestions or questions, or you need a feature, feel free to file an issue.

This project adheres to [Semantic Versioning](https://semver.org/).


## Example

```php
// Read frames from file.
$reader = new \Rhorber\ID3rw\Reader('/home/user/myfile.mp3');
$frames = $reader->getFrames();
print_r($frames);

// Change title.
$frames['TIT2']['information'] = '2018-10-21_'.$frames['TIT2']['information'];

// Write a new file (with modified title).
$writer = new \Rhorber\ID3rw\Writer();
$writer->writeNewFile($frames, '/home/user/mynewfile.mp3', '/home/user/myfile.mp3');
```


## License

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details


## TODO

Features I will add:
* Add functionality to `Reader` to return the frames as objects.
* Write tests for the `Writer`.
* Add more writing variants and expand their docs.
* Improve parsing of non-text frames.
* Return detailed info about frames (not only technical name).
* Improve readme.
* Eventually support more versions (2.3.0 and 2.4.0 are the most common ones).

- Add names and/or descriptions to the frames.
- Add the lists of frames (TCON, TFLT, TMED (TKEY), ETCO, APIC)
- Add notices when displaying TCOP and TPRO


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
