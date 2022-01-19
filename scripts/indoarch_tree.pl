#!/usr/bin/env perl

use strict;
use warnings;

no warnings 'experimental::signatures';
use experimental 'signatures';

use Encode;
use HTML::TreeBuilder;
use Text::Autoformat;

use DBI;

my $dsn      = "DBI:mysql:database=indoarch;host=localhost";
my $dbuser   = "indoarch";
my $dbpasswd = "ip31415";

my $dbh = DBI -> connect($dsn, $dbuser, $dbpasswd,
			 { RaiseError           => 1,
			   mysql_enable_utf8mb4 => 1
			 }
			);

my $rows = $dbh -> selectall_arrayref("select * from content order by id",
					      {Slice => {}}
					     );
my $orphans;

foreach my $row (@$rows) {

  my $content = $$row {body};

  my $tree = HTML::TreeBuilder -> new (implicit_body_p_tag => 1);
  $tree -> parse_content ($content);

  my @index = $$row {akey} =~
                  /^R=([0-9]+)\+S=([0-9]+)\+P=([0-9]+)\+M=([0-9]+)$/;

  die "Cannot find location parameters\n"
    if grep { ! defined $_} @index;

  # cleanup ($index [-1], $$row {id});

  my $body = $tree -> find ('body');
  next unless defined $body;

  print "(" . join (", ", @index) . ")\n";
  my $text = '';
  traverse_tree ($body, $$row {id}, $text);
  print "*" x 80 . "\n";

}

print "Found " . join(', ', @$orphans) . " orphans\n";

sub cleanup ($id, $content_id) {

  return unless $id;

  my $query = "select count(*) from structure where id = ?";
  my $row   = $dbh -> selectall_arrayref ($query, undef, $id);

  if (! $$row [0] [0]) {
    push @$orphans => $content_id;
    print "Structure $id does not exist\n";
  }
}

sub traverse_tree ($element, $id, $text) {

  my @child = $element -> content_list;
  return unless @child;

  # If this is <img> tag (and child of a <p> tag), we know that this
  # is the first elemnt of an an image-map. So we take the entire
  # $element and store it in the image-map column and insert its id in
  # the content table (every content has only one img map?)

  my $ins_query = "insert into maps (imgmap) values (?)";
  my $map_query = "insert into content (map) values (?) where id = ?";

  my $parent_tag = $element -> tag_name;
  foreach my $child (@child) {

    if (ref $child) {
      if (($parent_tag eq 'p') &&
	  ($child -> tag_name eq 'img')) {

	my $inserted = $dbh -> do ($query, undef, $element -> as_HTML ());
	my $map_id   = $dbh -> last_insert_id;

	$dbh -> do ($map_query, undef, $map_id, $id)
	  if $inserted;

	next;
      }

      return traverse_tree ($child, $id, $text);
    }
    else {
      my $content = encode("UTF-8", $child);

      $content =~ s/\\'//g;
      $content =~ s/\\"//g;

      print autoformat ($text, {
				left	 => 4,
				right	 => 80,
				justify => 'left',
				break	 => 'break_wrap',
			       }
		       );
    }
  }
}
