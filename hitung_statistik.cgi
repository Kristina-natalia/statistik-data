#!/usr/bin/perl
use CGI;
use CGI::Carp qw(fatalsToBrowser);
my $cgi = new CGI;

print $cgi->header("text/html");

my $data = $cgi->param("data");

open(my $pipe, "|./hitung_statistik") || die "Could not run program: $!\n";

print $pipe $data;

close($pipe);

while (<$pipe>) {
    print $_;
}
