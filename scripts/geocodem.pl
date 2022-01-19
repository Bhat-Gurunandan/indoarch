#!/usr/bin/env perl

use strict;
use warnings;

use DBI;
use Geo::Coder::OpenCage;
use JSON;
use Text::Autoformat;

use Data::Dumper;
$Data::Dumper::SOrtkeys = 1;

use constant GEOCODER_API_KEY =>
            '10c18edcd802489aba3fcd77756662d0';

my $dsn      = "DBI:mysql:database=indoarch;host=localhost";
my $dbuser   = "indoarch";
my $dbpasswd = "ip31415";

my $dbh = DBI -> connect($dsn, $dbuser, $dbpasswd,
			 { RaiseError           => 1,
			   mysql_enable_utf8mb4 => 1
			 }
			);

my $query = <<"    ---- END_QUERY";
    SELECT structure.id,
           structure.name,
           place.name,
           subregion.name
      FROM structure
 LEFT JOIN place     ON structure.parentid = place.id
 LEFT JOIN subregion ON place.parentid = subregion.id
  ORDER BY structure.id
    ---- END_QUERY

my $rows = $dbh -> selectall_arrayref($query, {});

my $geocoder = Geo::Coder::OpenCage
                   -> new (api_key => GEOCODER_API_KEY);

my @cols = qw[placeid formatted
	      lat lng
	      nelat nelng swlat swlng
	      preferred geocode];

my $ins_query;
# {
#   local $" = ', ';
#   $ins_query =<<"    ---- END_QUERY";
#     INSERT INTO geocode
#                 (@cols)
#          VALUES (@{[('?') x @cols]})
#     ---- END_QUERY
# }

print "$ins_query\n";

foreach my $row (@$rows) {

  my $structureid = shift @$row;

  # Title case
  $$row [1] = autoformat($$row [1], {case => 'title'});

  my $location = join(', ', @$row, 'India');

  my $info = $geocoder -> geocode (location    => $location,
				   language    => 'en',
				   countrycode => 'in');

  my $status     = $$info {status};
  my $preference = 0;
  my ($geocode, $inserted);

  if ($$status {message} eq 'OK') {

    print Dumper ($info);
    # # Save into database;
    # foreach my $result (@{$$info {results}}) {

    #   my $formatted = $$result {formatted};

    #   my $lat	    = $$result {geometry} {lat};
    #   my $lng	    = $$result {geometry} {lng};
    #   my $nelat	    = $$result {bounds} {northeast} {lat};
    #   my $nelng	    = $$result {bounds} {northeast} {lng};
    #   my $swlat	    = $$result {bounds} {southwest} {lat};
    #   my $swlng	    = $$result {bounds} {southwest} {lng};

    #   $geocode	    = encode_json $result;
    #   $inserted	    = $dbh -> do ($ins_query, undef,
    # 				  $placeid, $formatted,
    # 				  $lat, $lng,
    # 				  $nelat, $nelng, $swlat, $swlng,
    # 				  ++$preference, $geocode);
    # }
  }
  else {

    print "Cannot find geocode for " . $$row [1] . "\n";
    # $geocode	    = encode_json $status;
    # $inserted	    = $dbh -> do ($ins_query, undef,
    # 				  $placeid, undef,
    # 				  undef, undef,
    # 				  undef, undef, undef, undef,
    # 				  $preference, $geocode);
  }
}
