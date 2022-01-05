#!/usr/bin/env perl

use strict;
use warnings;

no warnings 'experimental::signatures';
use experimental 'signatures';

use Encode;
use HTML::TreeBuilder;
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

  cleanup ($index [-1], $$row {id});

  my $body = $tree -> find ('body');
  next unless defined $body;

  print "(" . join (", ", @index) . ")\n";
  traverse_tree ($body);
  print "*" x 80 . "\n";

}

print "Found " . join(', ', @$orphans) . " orphans\n";

sub cleanup ($id, $content_id) {

  return unless $id;

  my $row = $dbh -> selectall_arrayref ("select count(*) from structure where id = ?",
					 undef,
					 $id);
  if (! $$row [0] [0]) {
    push @$orphans => $content_id;
    print "Structure $id does not exist\n";
  }
}

sub traverse_tree ($element) {

  my @child = $element -> content_list;
  return unless @child;

  foreach my $child (@child) {

    if (ref $child) {
      print $child -> tag . "\n";
      traverse_tree ($child);
    }
    else {
      print encode("UTF-8", $child) . "\n";
    }
  }
}
